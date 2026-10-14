<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:80'],
            'month' => ['nullable', 'date_format:Y-m'],
            'destination' => $this->routeIs('gallery') ? ['nullable', 'integer', 'exists:destinations,id'] : ['nullable', 'string', 'max:120'],
        ];
    }
}
