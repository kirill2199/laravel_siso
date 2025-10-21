<?php

namespace App\Http\Controllers\Api;

use App\Models\Sale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalesController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $validated = $this->validateDates($request);
        
        $query = Sale::query()
            ->whereBetween('date', [
                $validated['dateFrom'],
                $validated['dateTo'] ?? $validated['dateFrom']
            ])
            ->orderBy('date', 'desc')
            ->orderBy('sale_id', 'desc');

        return $this->buildPaginatedResponse($query, $request);
    }
}