<?php

namespace App\Http\Controllers;

use App\Models\ParserConfig;
use App\Services\ParseService;
use Illuminate\Http\Request;

class ParseController extends Controller
{

    protected $parseService;

    public function __construct(ParseService $parseService)
    {
        $this->parseService = $parseService;
    }

    public function parseAllActive(Request $request)
    {
        try {
            $results = $this->parseService->parseAllActive($request);

            return response()->json([
                'success' => true,
                'results' => $results,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function parseSingleConfig(ParserConfig $config)
    {
        try {
            return response()->json([
                'success' => true,
                'results' => $this->parseService->parseSingleConfig($config),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
