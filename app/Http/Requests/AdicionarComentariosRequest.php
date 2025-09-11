<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdicionarComentariosRequest extends FormRequest
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
            'descricao' => 'required|string',
            'tipo' => 'in:comment,edit,solution',
            'changes' => 'nullable|json',
            'anexos' => 'nullable|array',
            'anexos.*' => 'file|max:153600|mimes:jpeg,png,pdf,zip,rar|mimetypes:application/pdf,image/jpeg,image/png,application/zip,application/x-rar-compressed,video/mp4',
        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required' => 'O campo descrição é obrigatório.',
            'descricao.string' => 'O campo descrição deve ser uma string.',
            'tipo.in' => 'O campo tipo deve ser um dos seguintes valores: comment, edit, solution.',
            'changes.json' => 'O campo changes deve ser um JSON válido.',
            'anexos.array' => 'O campo anexos deve ser um array de arquivos.',
            'anexos.*.file' => 'Cada anexo deve ser um arquivo válido.',
            'anexos.*.max' => 'Cada anexo não pode exceder 150MB.',
            'anexos.*.mimes' => 'Os anexos devem ser do tipo: jpeg, png, pdf, zip, rar, mp4.',
            'anexos.*.mimetypes' => 'Os anexos devem ser do tipo: application/pdf, image/jpeg, image/png, application/zip, application/x-rar-compressed, video/mp4.',
        ];
    }
}
