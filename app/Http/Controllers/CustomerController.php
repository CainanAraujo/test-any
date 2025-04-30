<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerRequest;

class CustomerController extends Controller
{
    // Listagem com filtros por nome e e-mail
    public function index(Request $request)
    {
        $q = Customer::query();

        if ($request->filled('name')) {
            $q->where('name', 'like', '%'.$request->name.'%');
        }
        if ($request->filled('email')) {
            $q->where('email', 'like', '%'.$request->email.'%');
        }

        return response()->json($q->get());
    }

    // Cadastrar cliente
    public function store(CustomerRequest $request)
    {
        $customer = Customer::create($request->validated());
        return response()->json($customer, 201);
    }

    // Exibir um cliente
    public function show(Customer $customer)
    {
        return response()->json($customer);
    }

    // Atualizar cliente
    public function update(CustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());
        return response()->json($customer);
    }

    // Deletar cliente
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json(null, 204);
    }
}
