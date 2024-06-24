<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                Rule::unique(Customer::class)->ignore($this->input('id')),
            ],
            'phone_number' => [
                'nullable',
                'string',
                'min:12',
                'max:13',
                Rule::unique(Customer::class)->ignore($this->input('id')),
            ],
            'birthday' => [
                'nullable',
                'date',
                'before_or_equal:' . Carbon::now()->subYears(16),
                'after_or_equal:' . Carbon::now()->subYears(100),
            ],
            'gender' => [
                'nullable',
                'integer',
                Rule::in(Gender::keys()),
            ],
            'timezone' => [
                'required',
                'string',
                'max:30',
                Rule::in(timezone_identifiers_list()),
            ],
            'addresses.*.default' => [
                'required',
                'boolean',
            ],
            'addresses.*.type' => [
                'required',
                'integer',
                Rule::in(\App\Enums\Address\Customer::keys()),
            ],
            'addresses.*.country' => [
                'required',
                'string',
                'max:100',
            ],
            'addresses.*.province' => [
                'required',
                'string',
                'max:100',
            ],
            'addresses.*.district' => [
                'required',
                'string',
                'max:100',
            ],
            'addresses.*.ward' => [
                'required',
                'string',
                'max:100',
            ],
            'addresses.*.address_detail' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}
