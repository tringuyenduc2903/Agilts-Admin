<?php

namespace App\Http\Requests;

use App\Enums\Address\Branch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
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
                'max:255',
            ],
            'phone_number' => [
                'nullable',
                'string',
                'min:8',
                'max:10',
                Rule::unique(\App\Models\Branch::class)->ignore($this->input('id')),
            ],
            'addresses' => [
                'required',
            ],
            'addresses.*.default' => [
                'required',
                'boolean',
            ],
            'addresses.*.type' => [
                'required',
                'integer',
                Rule::in(Branch::keys()),
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
                'nullable',
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
