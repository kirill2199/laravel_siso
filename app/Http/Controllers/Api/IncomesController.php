<?php

namespace App\Http\Controllers\Api;

use App\Models\Income;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IncomesController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $validated = $this->validateDates($request);
        
        $query = Income::query()
            ->whereBetween('date', [
                $validated['dateFrom'],
                $validated['dateTo'] ?? $validated['dateFrom']
            ])
            ->orderBy('date', 'desc')
            ->orderBy('income_id', 'desc');

        return $this->buildPaginatedResponse($query, $request);
    }
}