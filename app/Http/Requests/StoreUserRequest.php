<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|unique:user',
            'phone' => 'required|unique:user',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Không được để trống',
            'email.required' => 'Không được để trống',
            'phone.required' => 'Không được để trống',
        ];
    }
}
