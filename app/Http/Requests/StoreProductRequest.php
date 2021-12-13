<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|unique:products,name',
            'price' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Product name not valid',
            'name.unique' => 'Product name already exists! Retry!',
            'price.unique' => 'Product price not valid',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $errors = json_decode($validator->errors(), true);
        foreach ($errors as $error) {
            $msg = $error[0];
            break;
        }

        $response = [
            'code' => 'error',
            'message' => $msg
        ];
        throw new HttpResponseException(response()->json($response));
    }
}
