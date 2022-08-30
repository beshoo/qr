<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|max:25',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function getAttributes() {
        return $this->validated();
    }

}
