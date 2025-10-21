<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

abstract class ApiController extends Controller
{
    protected function validateDates(Request $request, $requireTo = true)
    {
        return $request->validate([
            'dateFrom' => 'required|date_format:Y-m-d',
            'dateTo' => $requireTo ? 'required|date_format:Y-m-d|after_or_equal:dateFrom' : 'nullable|date_format:Y-m-d|after_or_equal:dateFrom',
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:500'
        ]);
    }

    protected function getLimit(Request $request)
    {
        return min($request->get('limit', 500), 500);
    }

    /**
     * Создает пагинированный ответ в нужном формате
     */
    protected function buildPaginatedResponse($query, Request $request): JsonResponse
    {
        $limit = $this->getLimit($request);
        $page = $request->get('page', 1);

        $paginator = $query->paginate($limit, ['*'], 'page', $page);
        
        return response()->json([
            'data' => $paginator->items(),
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'from' => $paginator->firstItem(),
                'last_page' => $paginator->lastPage(),
                'links' => $this->formatMetaLinks($paginator),
                'path' => $request->url(),
                'per_page' => $paginator->perPage(),
                'to' => $paginator->lastItem(),
                'total' => $paginator->total(),
            ]
        ]);
    }

    /**
     * Форматирует ссылки для meta раздела
     */
    protected function formatMetaLinks($paginator): array
    {
        $links = [];

        // Previous link
        $links[] = [
            'url' => $paginator->previousPageUrl(),
            'label' => '&laquo; Previous',
            'active' => false,
        ];

        // Page links
        for ($page = 1; $page <= $paginator->lastPage(); $page++) {
            $links[] = [
                'url' => $paginator->url($page),
                'label' => (string)$page,
                'active' => $page === $paginator->currentPage(),
            ];
        }

        // Next link
        $links[] = [
            'url' => $paginator->nextPageUrl(),
            'label' => 'Next &raquo;',
            'active' => false,
        ];

        return $links;
    }
}