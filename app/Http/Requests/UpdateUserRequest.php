<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $id = $this->route('id');
        return [
            'name' => 'sometimes|filled|max:255',
            'email' => 'sometimes|filled|max:255|unique:users,email,' . $id,'login',
            'login' => 'sometimes|filled|max:255|unique:users,login,' . $id,
            'password' => 'prohibited',
            'adm' => 'sometimes|boolean'
        ];
    }
}
