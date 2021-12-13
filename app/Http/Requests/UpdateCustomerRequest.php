<?php

namespace App\Http\Requests;

use App\Repositories\Customers\CustomerRepository;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateCustomerRequest extends FormRequest
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
        $customerRepo = new CustomerRepository();
        $check = $customerRepo->isExistCustomer($this->id);
        if (!$check) {
            return [];
        }

        return [
            'name' => 'required',
            'phone' => 'required|unique:customers,phone,' . $this->id,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'name required',
            'phone.exists' => 'Id not found',
            'phone.required' => 'phone required',
            'phone.unique' => 'phone is exist! Re try'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = [];
        $errors = json_decode($validator->errors(), true);
        foreach ($errors as $error) {
            $message = $error[0];
            $response = [
                'code' => 'error',
                'message' => $message
            ];
            break;
        }

        throw new HttpResponseException(response()->json($response));
    }
}
