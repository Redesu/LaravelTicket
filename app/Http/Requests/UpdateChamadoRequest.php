<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChamadoRequest extends FormRequest
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
            'id' => 'required|numeric|exists:chamados,id',
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string|max:100',
            'prioridade' => 'required|string|max:100',
            'status' => 'required|string|max:100',
            'categoria' => 'required|string|min:1',
            'departamento' => 'required|string|min:1'
        ];
    }
}
