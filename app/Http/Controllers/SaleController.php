<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class SaleController extends Controller
{
    /**
     * Registra uma nova venda.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'customer_id' => ['required','integer', Rule::exists('customers','id')],
            'amount'      => ['required','numeric','min:0.01'],
            'sold_at'     => ['nullable','date'],  // se não vier, default no model/migration
        ]);

        $sale = Sale::create($data);

        return response()->json($sale, 201);
    }

    // Opcional: listar vendas
    public function index(Request $request): JsonResponse
    {
        $q = Sale::query();
        if ($request->filled('customer_id')) {
            $q->where('customer_id', $request->customer_id);
        }
        return response()->json($q->get());
    }
}
