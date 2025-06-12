<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EditCustomerRequest extends FormRequest
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
        $user = $this->route('user');

        return [
            'edit_name' => 
            [
                'sometimes', 
                'string', 
                'max:255'
            ],
            'edit_customer_id' => 
            [
                'sometimes', 
                'numeric'
            ],
            'edit_email' => 
            [
                'sometimes', 
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ], 
            
// === Optional Profile Fields ===
            'edit_account_name' => [
                'nullable', 
                'string', 
                'max:100'
            ],

            'edit_short_name' => [
                'nullable', 
                'string', 
                'max:100'
            ],

            'edit_customer_category' => [
                'nullable', 
                'string', 
                'max:255'
            ],

            'edit_contract_price' => [
                'nullable', 
                'numeric'
            ],

            'edit_contracted_demand' => [
                'nullable', 
                'numeric'
            ],
            'edit_cooperation_period_start_date' => [
                'nullable', 
                'date'
            ],
            'edit_cooperation_period_end_date' => [
                'nullable', 
                'date'
            ],
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        session()->flash('show_modal', 'edit-customer-modal'); // 👈 Add this
    
        throw new HttpResponseException(
            redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
        );
    }
}
