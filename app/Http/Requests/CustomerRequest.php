<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;

/**
 * FormRequest para criação e atualização de clientes.
 *
 * - Em POST (store), o email é obrigatório.
 * - Em PUT/PATCH (update), o email é validado somente se enviado.
 *
 * @method Route|null route(string $key = null)
 * @method bool isMethod(string $method)
 */
class CustomerRequest extends FormRequest
{
    /**
     * Diz se o usuário está autorizado a fazer esta requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para criação e atualização.
     */
    public function rules(): array
    {
        // Obtém o ID do cliente (ou nulo se for criação)
        $id = optional($this->route('customer'))->id;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
        ];

        // Em criação, email é obrigatório e único
        if ($this->isMethod('post')) {
            $rules['email'] = ['required', 'email', 'unique:customers,email'];
        } else {
            // Em atualização, email só é validado se presente
            $rules['email'] = ['sometimes', 'required', 'email', "unique:customers,email,{$id}"];
        }

        // Telefone é opcional
        $rules['phone'] = ['nullable', 'string', 'max:20'];

        return $rules;
    }
}
