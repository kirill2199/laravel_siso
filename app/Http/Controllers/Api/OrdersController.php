<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrdersController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $validated = $this->validateDates($request);
        
        $query = Order::query()
            ->whereBetween('date', [
                $validated['dateFrom'],
                $validated['dateTo'] ?? $validated['dateFrom']
            ])
            ->orderBy('date', 'desc')
            ->orderBy('odid', 'desc');

        return $this->buildPaginatedResponse($query, $request);
    }
}