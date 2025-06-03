<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreMilesOperationRequest extends FormRequest
{
    /**
     * Autoriza a requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para a requisição.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:70',
            'description' => 'required',
        ];
    }
}
