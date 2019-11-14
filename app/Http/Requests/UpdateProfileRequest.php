<?php

namespace App\Http\Requests;

// use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\ValidasiRequest;
use JWTAuth;

class UpdateProfileRequest extends ValidasiRequest
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
        $user = JWTAuth::parseToken()->authenticate();
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id
        ];
    }
}
