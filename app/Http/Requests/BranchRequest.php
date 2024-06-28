<?php

namespace App\Http\Requests;

use App\Models\Branch;
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
                Rule::unique(Branch::class)->ignore($this->input('id')),
            ],
            'address.default' => [
                'required',
                'boolean',
            ],
            'address.type' => [
                'required',
                'integer',
            ],
            'address.country' => [
                'required',
                'string',
                'max:100',
            ],
            'address.province' => [
                'required',
                'string',
                'max:100',
            ],
            'address.district' => [
                'required',
                'string',
                'max:100',
            ],
            'address.ward' => [
                'nullable',
                'string',
                'max:100',
            ],
            'address.address_detail' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}
