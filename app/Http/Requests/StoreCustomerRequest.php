<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
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
            'name' => [
                'required',
                'string',

            ],
            'email' => [
                'required',
                'email',
                Rule::unique(User::class)
            ],

            'customer_id' => [
                'required',
                'numeric',
            ],

            // === Optional Profile Fields ===
            'account_name' => [
                'nullable', 
                'string', 
                'max:100'
            ],

            'short_name' => [
                'nullable', 
                'string', 
                'max:100'
            ],

            'customer_category' => [
                'nullable', 
                'string', 
                'max:255'
            ],

            'contract_price' => [
                'nullable', 
                'numeric'
            ],

            'contracted_demand' => [
                'nullable', 
                'numeric'
            ],
            'cooperation_period_start_date' => [
                'nullable', 
                'date'
            ],
            'cooperation_period_end_date' => [
                'nullable', 
                'date'
            ],

        ];
    }
    protected function failedValidation(Validator $validator)
    {
        session()->flash('show_modal', 'customer-modal'); 
        throw new HttpResponseException(
            redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
        );
    }
}
