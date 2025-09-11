<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChamadoRequest extends FormRequest
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
            'prioridade' => 'required|string|max:100',
            'categoria_id' => 'required|integer|min:1',
            'departamento_id' => 'required|integer|min:1',
            'user_id' => 'required|integer|exists:users,id',
            'anexos' => 'nullable|array',
            'anexos.*' => 'file|max:153600|mimes:jpeg,png,pdf,zip,rar,mp4|mimetypes:application/pdf,image/jpeg,image/png,application/zip,application/x-rar-compressed,video/mp4',
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'O campo título é obrigatório.',
            'titulo.string' => 'O campo título deve ser uma string.',
            'titulo.max' => 'O campo título não pode exceder 255 caracteres.',
            'descricao.required' => 'O campo descrição é obrigatório.',
            'descricao.string' => 'O campo descrição deve ser uma string.',
            'descricao.max' => 'O campo descrição não pode exceder 100 caracteres.',
            'prioridade.required' => 'O campo prioridade é obrigatório.',
            'prioridade.string' => 'O campo prioridade deve ser uma string.',
            'prioridade.max' => 'O campo prioridade não pode exceder 100 caracteres.',
            'categoria_id.required' => 'O campo categoria é obrigatório.',
            'categoria_id.integer' => 'O campo categoria deve ser um número inteiro.',
            'categoria_id.min' => 'O campo categoria deve ser no mínimo 1.',
            'departamento_id.required' => 'O campo departamento é obrigatório.',
            'departamento_id.integer' => 'O campo departamento deve ser um número inteiro.',
            'departamento_id.min' => 'O campo departamento deve ser no mínimo 1.',
            'user_id.required' => 'O campo usuário é obrigatório.',
            'user_id.integer' => 'O campo usuário deve ser um número inteiro.',
            'user_id.exists' => 'O usuário selecionado não existe.',
            'anexos.array' => 'O campo anexos deve ser um array de arquivos.',
            'anexos.*.file' => 'Cada anexo deve ser um arquivo válido.',
            'anexos.*.max' => 'Cada anexo não pode exceder 150MB.',
            'anexos.*.mimes' => 'Os anexos devem ser do tipo: jpeg, png, pdf, zip, rar, mp4.',
            'anexos.*.mimetypes' => 'Os anexos devem ser do tipo: application/pdf, image/jpeg, image/png, application/zip, application/x-rar-compressed, video/mp4.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'categoria_id' => (int) $this->categoria_id,
            'departamento_id' => (int) $this->departamento_id
        ]);
    }
}
