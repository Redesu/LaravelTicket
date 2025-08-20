<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DataTableChamadoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            // dataTable parameters
            'draw' => ['nullable', 'integer'],
            'start' => ['nullable', 'integer', 'min:0'],
            'length' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'search.value' => ['nullable', 'string', 'max:255'],

            // filter parameters
            'status' => ['nullable', 'string', 'in:Aberto,Em andamento,Finalizado'],
            'prioridade' => ['nullable', 'string', 'in:Baixa,Média,Alta,Urgente'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'departamento' => ['nullable', 'string', 'in:SUPORTE,DESENVOLVIMENTO'],
            'categoria' => ['nullable', 'string', 'in:SUPORTE,CORREÇÃO,DUVIDAS'],
            'created_at_inicio' => ['nullable', 'date'],
            'created_at_fim' => ['nullable', 'date'],
            'updated_at_inicio' => ['nullable', 'date'],
            'updated_at_fim' => ['nullable', 'date']
        ];
    }
}
