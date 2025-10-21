<?php

namespace App\Http\Controllers\Api;

use App\Models\Stock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StocksController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'dateFrom' => 'required|date_format:Y-m-d',
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:500',
        ]);

        // Выгрузка только за текущий день
        $today = now()->format('Y-m-d');
        if ($validated['dateFrom'] !== $today) {
            return response()->json([
                'error' => 'Invalid date',
                'message' => 'Stocks can only be exported for current day'
            ], 400);
        }

        $query = Stock::query()
            ->where('date', $today)
            ->orderBy('warehouse_name')
            ->orderBy('nm_id');

        return $this->buildPaginatedResponse($query, $request);
    }
}