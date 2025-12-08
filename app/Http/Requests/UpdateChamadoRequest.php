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
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string|max:100',
            'prioridade' => 'required|in:Baixa,Média,Alta,Urgente',
            'status' => 'required|string|max:100',
            'categoria_nome' => 'required|string|exists:categorias,nome',
            'departamento_nome' => 'required|string|exists:departamentos,nome',
            'user_id' => 'required|integer|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'titulo.required' => 'O campo título é obrigatório.',
            'descricao.required' => 'O campo descrição é obrigatório.',
            'prioridade.required' => 'O campo prioridade é obrigatório.',
            'prioridade.in' => 'A prioridade deve ser Baixa, Média, Alta ou Urgente.',
            'status.required' => 'O campo status é obrigatório.',
            'categoria_nome.required' => 'O campo categoria é obrigatório.',
            'departamento_nome.required' => 'O campo departamento é obrigatório.',
            'user_id.required' => 'O campo usuário é obrigatório.',
        ];
    }
}
