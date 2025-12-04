<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArtistRequest extends FormRequest
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
        if($this->method() == 'PATCH'){
            return [
                'name' => 'nullable|string|min:2',
                'nationality' => 'nullable|string|min:2',
                'image' => 'nullable|string|min:10',
                'description' => 'nullable|string|min:10',
                'is_band' => 'nullable|string|min:2',
            ];
        }
        return [
            'name' => 'required|string|min:2',
            'nationality' => 'required|string|min:2',
            'image' => 'nullable|string|min:10',
            'description' => 'nullable|string|min:10',
            'is_band' => 'required|string|min:2',
        ];
    }
}
