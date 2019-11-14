<?php

namespace App\Http\Requests;

use App\Http\Requests\ValidasiRequest;

class ChangePasswordRequest extends ValidasiRequest
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
            'old_password' => 'required',
            'password' => 'required|different:old_password|confirmed',
            'password_confirmation' => 'required|same:password'
        ];
    }
}
