<?php

namespace App\Http\Controllers;

use App\Models\ParserConfig;
use App\Models\Pharmacy;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class ParseController extends Controller
{
    public function parseAllActive()
    {
        set_time_limit(3600);

        $configs = ParserConfig::where('is_active', true)->get();
        $results = [];

        foreach ($configs as $config) {
            $response = $this->getGetResponse($config);

            if ($config->has_post) {
                $postData = $this->getPostData($config, $response);

                $urls = $this->getPrepareUrls($config, $postData);

                foreach ($urls as $url) {
                    $response = $config->method == "GET" ? $this->getGetResponse($config, $url) : $this->getPostResponse($config, $url);

                    if ($config->response_form == 'html') {
                        $results[$config->id][] = $this->parseHtmlResponse($config, $response, $url);
                    } elseif ($config->response_form == 'json') {
                        $results[$config->id][] = $this->parseJsonResponse($config, $response);
                    }
                }
            } else {
                $results[$config->id][] = $this->parseHtmlResponse($config, $response);
            }
        }

        return response()->json([
            'success' => true,
            'results' => $results,
        ]);
    }


    public function getPrepareUrls(ParserConfig $config, $postData)
    {
        $urls = [];
        if ($config->params_to == "url") {
            foreach ($postData as $item) {
                $urls[] = sprintf(
                    $config->post_url,
                    $item['value']
                );
            }
        } elseif ($config->params_to == "body") {
            $postParams = $config->post_params;

            foreach ($postData as $item) {
                $modifiedParams = $postParams;
                foreach ($modifiedParams as &$value) {
                    if ($value == 'value') {
                        $value = $item['value'];
                    }
                }
                unset($value);

                $urls[] = [
                    $config->post_url,
                    $modifiedParams
                ];
            }
        }

        return $urls;
    }

    public function getPostData(ParserConfig $config, $html)
    {
        $postData = null;

        if ($config->params_from == "html") {
            $postData = $this->getAjaxFormData($config, $html);
        } elseif ($config->params_from == "custom") {
            $postData = [$config->post_params];
        }

        return $postData;
    }

    public function getGetResponse(ParserConfig $config, $url = null)
    {
        try {
            if ($config->has_js) {
                $apiUrl = sprintf(
                    'https://app.scrapingbee.com/api/v1?api_key=%s&url=%s',
                    env('SCRAPINGBEE_API_KEY'),
                    $url ?? $config->url
                );
                $html = file_get_contents($apiUrl);
            } else {
                $browser = new HttpBrowser(HttpClient::create());
                $browser->request('GET', $url ?? $config->url);

                sleep(null);

                $html = $browser->getResponse()->getContent();
            }
            return $html;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    protected function getPostResponse(ParserConfig $config, $url = null)
    {
        $client = HttpClient::create();

        $response = $client->request($config->method, $url[0], [
            'body' => $url[1]
        ]);
        return $response->getContent();
    }

    public function getAjaxFormData($config, $html)
    {
        $crawler = new Crawler($html);

        $filter = $config->ajax_selectors['form_selector'];

        $optionSelector = $config->ajax_selectors['option_selector'];
        preg_match('/^([^\[]+)(?:\[([^\]]+)\])?$/', $optionSelector, $matches);
        $element = $matches[1] ?? '';
        $attribute = $matches[2] ?? '';
        $attribute = $attribute ?: 'value';

        $ajaxFormData = $crawler->filter($filter)->filter($optionSelector)
            ->each(function (Crawler $node) use ($attribute) {
                if ($node->attr('disabled') !== null) {
                    return null;
                }

                $value = $node->attr($attribute) ?? '';

                return [
                    'value' => $value,
                    'text' => $node->text(),
                ];
            });

        $ajaxFormData = array_values(array_filter($ajaxFormData, function ($item) {
            return $item !== null;
        }));

        return $ajaxFormData;
    }

    public function parseHtmlResponse($config, $html, $url = null)
    {
        try {
            $crawler = new Crawler($html);

            $pharmacyNodes = $crawler->filter($config->selectors['filter'])->each(function (Crawler $node) use ($config, $url) {
                $url = $url ? $url : $config->url;
                $url = is_array($url) ? $url[0] : $url;

                if (!empty($config->selectors)) {
                    $data = [
                        'name' => $this->extractDataFromNode($node, $config->selectors['name'], true),
                        'address' => $this->extractDataFromNode($node, $config->selectors['address']),
                        'phone' => $this->extractDataFromNode($node, $config->selectors['phone'] ?? null),
                        'opening_hours' => $this->extractDataFromNode($node, $config->selectors['working_hours'] ?? null),
                        'website' => $url,
                    ];
                } else {
                    $data = [
                        'name' => $node,
                    ];
                }

                return $this->applyMapping($data, $config->mapping);
            });

            $savedPharmacies = [];
            foreach ($pharmacyNodes as $mappedData) {
                if (empty($mappedData['name'])) {
                    continue;
                }

                $pharmacy = Pharmacy::updateOrCreate(
                    [
                        'name' => trim($mappedData['name']),
                        'address' => trim($mappedData['address'] ?? null),
                    ],
                    [
                        'name' => trim($mappedData['name']),
                        'address' => trim($mappedData['address'] ?? null),
                        'phone' => $mappedData['phone'] ?? null,
                        'opening_hours' => $mappedData['opening_hours'] ?? null,
                        'website' => $mappedData['website'] ?? null,
                        'latitude' => isset($mappedData['latitude']) ? (float)$mappedData['latitude'] : null,
                        'longitude' => isset($mappedData['longitude']) ? (float)$mappedData['longitude'] : null,
                    ]
                );

                $savedPharmacies[] = $pharmacy;
            }

            $config->update(['last_parsed_at' => now()]);

            return response()->json([
                'success' => true,
                'count' => count($savedPharmacies),
                'pharmacies' => $savedPharmacies,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    protected function parseJsonResponse(ParserConfig $config, $json, $url = null)
    {
        $json = trim($json);

        $json = mb_convert_encoding($json, 'UTF-8', 'UTF-8');

        if (json_last_error() !== JSON_ERROR_NONE) {
            $errorMsg = 'JSON decode error: ' . json_last_error_msg();
            $errorMsg .= "\nJSON snippet: " . substr($json, 0, 200) . '...';

            throw new \Exception($errorMsg);
        }

        if (!empty($config->json_clear_params)) {
            $jsonClearParams = $config->json_clear_params;
            foreach ($jsonClearParams as $prefix => $suffix) {
                $escapedPrefix = preg_quote($prefix, '/');
                $escapedSuffix = preg_quote($suffix, '/');

                $json = preg_replace("/^{$escapedPrefix}|{$escapedSuffix}$/", '', $json);
            }
        }
        $data = json_decode($json, true);

        $pharmacies = [];

        foreach ($data as $item) {
            $mappedData = [
                'name' => $this->getJsonValue($item, $config->json_paths['name'] ?? null),
                'address' => $this->getJsonValue($item, $config->json_paths['address'] ?? null),
                'phone' => $this->getJsonValue($item, $config->json_paths['phone'] ?? null),
                'opening_hours' => $this->getJsonValue($item, $config->json_paths['opening_hours'] ?? null),
                'latitude' => $this->getJsonValue($item, $config->json_paths['latitude'] ?? null),
                'longitude' => $this->getJsonValue($item, $config->json_paths['longitude'] ?? null),
                'website' => $url ?: $config->url,
            ];

            if (empty($mappedData['name'])) {
                continue;
            }

            $pharmacy = Pharmacy::updateOrCreate(
                [
                    'name' => trim($mappedData['name']),
                    'address' => trim($mappedData['address']),
                ],
                [
                    'phone' => $mappedData['phone'] ?? null,
                    'opening_hours' => $mappedData['opening_hours'] ?? null,
                    'website' => $mappedData['website'] ?? null,
                    'latitude' => isset($mappedData['latitude']) ? (float)$mappedData['latitude'] : null,
                    'longitude' => isset($mappedData['longitude']) ? (float)$mappedData['longitude'] : null,
                ]
            );

            $pharmacies[] = $pharmacy;
        }

        $config->update(['last_parsed_at' => now()]);

        return response()->json([
            'success' => true,
            'count' => count($pharmacies),
            'pharmacies' => $pharmacies,
        ]);
    }

    protected function getJsonValue(array $data, ?string $path)
    {
        if (!$path) {
            return null;
        }

        $keys = explode('.', $path);

        $value = $data;

        foreach ($keys as $key) {
            if (!isset($value[$key])) {
                return null;
            }
            $value = $value[$key];
        }

        return is_array($value) ? json_encode($value) : $value;
    }

    protected function extractDataFromNode(Crawler $node, ?string $selector, bool $isNameField = false)
    {
        if (!$selector) {
            return null;
        }

        try {
            $elements = $node->filter($selector);

            if ($elements->count() === 0) {
                return null;
            }

            $text = $elements->first()->text();
            $text = trim(preg_replace('/\s+/', ' ', $text));

            if ($isNameField) {
                if (preg_match('/^(.*?)(\d{2}\/\d{2}\/\d{4}.*)$/', $text, $matches)) {
                    $namePart = trim($matches[1]);
                    $datePart = trim($matches[2]);

                    $node->attr('data-extracted-date', $datePart);

                    return $namePart;
                }
            }

            return $text;
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function applyMapping(array $data, array $mapping): array
    {
        $result = [];

        foreach ($mapping as $key => $field) {
            if (isset($data[$key])) {
                $result[$field] = $data[$key];
            }
        }

        return $result;
    }
}
