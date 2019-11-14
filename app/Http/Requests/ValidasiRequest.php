<?php

namespace App\Http\Requests;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
abstract class ValidasiRequest extends FormRequest
{
    
    abstract public function rules();
    abstract public function authorize();

    public function validation_message($validasi)
    {
        $error = array();
        foreach ($validasi as $key => $value) {
            $error[$key] = $value[0];
        }
        return $error;
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $contents = $this->validation_message($errors);
        $status['code'] = 404;
        $validasi = $validator->messages()->toArray();

        $first_value = reset($contents); //mengambil array pertama dari validasi message
        $status['message'] = $first_value; // menampilkan validasi pada response
        $status['contents'] = $validasi; // menampilkan semua list validasi

        throw new HttpResponseException(response()->json($status, JsonResponse::HTTP_NOT_FOUND)); // mengembalikan respon status kode 404
    }
}
