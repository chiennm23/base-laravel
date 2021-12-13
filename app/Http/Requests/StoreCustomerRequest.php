<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'phone' => 'required|unique:customers',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'name required',
            'phone.required' => 'phone required',
            'phone.unique' => 'phone is not valid'
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
