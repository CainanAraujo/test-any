<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * Retorna o total de vendas por dia.
     */
    public function dailySales(): JsonResponse
    {
        $data = Sale::selectRaw('DATE(sold_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($data);
    }

    /**
     * Retorna:
     * - cliente com maior volume total de vendas
     * - cliente com maior ticket médio
     * - cliente com maior frequência de dias únicos com vendas
     */
    public function topCustomers(): JsonResponse
    {
        // Cliente com maior volume total de vendas
        $topVolume = Customer::withSum('sales', 'amount')
            ->orderByDesc('sales_sum_amount')
            ->first();

        // Cliente com maior ticket médio (média de amount por venda)
        $topAverage = Customer::withAvg('sales', 'amount')
            ->orderByDesc('sales_avg_amount')
            ->first();

        // Cliente com maior número de dias únicos em que comprou
        $topFrequency = Customer::withCount([
            'sales as unique_days' => function ($query) {
                $query->select(DB::raw('COUNT(DISTINCT DATE(sold_at))'));
            },
        ])
        ->orderByDesc('unique_days')
        ->first();

        return response()->json([
            'top_volume'    => $topVolume,
            'top_average'   => $topAverage,
            'top_frequency' => $topFrequency,
        ]);
    }
}
