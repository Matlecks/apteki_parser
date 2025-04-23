<?php

namespace App\Http\Controllers;

use App\Models\ParserConfig;
use App\Models\Pharmacy;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class ParseController extends Controller
{

    // Метод для парсинга всех активных конфигураций
    public function parseAllActive()
    {
        $configs = ParserConfig::where('is_active', true)->get();
        $results = [];

        foreach ($configs as $config) {
            $results[$config->id] = $this->parse($config);
        }

        return response()->json([
            'success' => true,
            'results' => $results,
        ]);
    }

    public function parse(ParserConfig $config)
    {
//        $config = ParserConfig::find(3);
        try {
            if ($config->has_js) {
                $apiUrl = sprintf(
                    'https://app.scrapingbee.com/api/v1?api_key=%s&url=%s',
                    env('SCRAPINGBEE_API_KEY'),
                    $config->url
                );
                $html = file_get_contents($apiUrl);
            } else {
                $browser = new HttpBrowser(HttpClient::create());
                $browser->request('GET', $config->url);

                sleep(null);

                $html = $browser->getResponse()->getContent();
            }

            $crawler = new Crawler($html);

            dump($config->name);
            dump($config->url);
            dump($html);
            dd($crawler->filter($config->ajax_selectors['option_selector'])
                ->each(function (Crawler $node) {
                    if ($node->attr('disabled') !== null) {
                        return null;
                    }
                    return [
                        'value' => $node->attr('value'),
                        'text' => $node->text(),
                    ];
                })
        );
            // Определяем контейнер, в котором находятся отдельные аптеки
            // (это может быть, например, '.pharmacy-item' или другой селектор)
            $pharmacyNodes = $crawler->filter($config->selectors['filter'])->each(function (Crawler $node) use ($config) {
                // Для каждого узла аптеки извлекаем данные
                $data = [
                    'name' => $this->extractDataFromNode($node, $config->selectors['name'], true),
                    'address' => $this->extractDataFromNode($node, $config->selectors['address']),
                    'phone' => $this->extractDataFromNode($node, $config->selectors['phone'] ?? null),
                    'opening_hours' => $this->extractDataFromNode($node, $config->selectors['working_hours'] ?? null),
                    'website' => $this->extractDataFromNode($node, $config->selectors['website'] ?? null),
                ];

                return $this->applyMapping($data, $config->mapping);
            });

            $savedPharmacies = [];
            foreach ($pharmacyNodes as $mappedData) {
                if (empty($mappedData['name'])) {
                    continue; // Пропускаем если нет названия
                }

                $pharmacy = Pharmacy::updateOrCreate(
                    [
                        'name' => trim($mappedData['name']),
                        'address' => trim($mappedData['address']),
                    ],
                    [
                        'name' => trim($mappedData['name']),
                        'address' => trim($mappedData['address']),
                        'phone' => $mappedData['phone'] ?? null,
                        'opening_hours' => $mappedData['opening_hours'] ?? null,
                        'website' => $mappedData['website'] ?? null,
                        'latitude' => isset($mappedData['latitude']) ? (float)$mappedData['latitude'] : null,
                        'longitude' => isset($mappedData['longitude']) ? (float)$mappedData['longitude'] : null,
                    ]
                );

                $savedPharmacies[] = $pharmacy;
            }

            // Обновляем время последнего парсинга
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

    protected function extractDataFromNode(Crawler $node, ?string $selector, bool $isNameField = false)
    {
        if (!$selector) {
            return null;
        }

        try {
            // Извлекаем все элементы, соответствующие селектору в пределах текущего узла
            $elements = $node->filter($selector);

            if ($elements->count() === 0) {
                return null;
            }

            // Для простых полей берем первый элемент
            $text = $elements->first()->text();
            $text = trim(preg_replace('/\s+/', ' ', $text));

            if ($isNameField) {
                // Split on the last date pattern (assuming date is at the end)
                if (preg_match('/^(.*?)(\d{2}\/\d{2}\/\d{4}.*)$/', $text, $matches)) {
                    $namePart = trim($matches[1]);
                    $datePart = trim($matches[2]);

                    // Store the date part in the node for later mapping
                    $node->attr('data-extracted-date', $datePart);

                    return $namePart;
                }
            }

            // Очищаем текст от лишних пробелов и переносов строк
            return $text;
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function extractData(Crawler $crawler, ?string $selector)
    {
        if (!$selector) {
            return null;
        }

        try {
            return $crawler->filter($selector)->first()->text();
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
