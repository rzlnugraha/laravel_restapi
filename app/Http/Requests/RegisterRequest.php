<?php

namespace App\Http\Requests;

use App\Http\Requests\ValidasiRequest;

class RegisterRequest extends ValidasiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Harus pake nama',
            'email.required' => 'Email harus ada',
            'email.unique' => 'Ulah sarua'
        ];
    }
}
