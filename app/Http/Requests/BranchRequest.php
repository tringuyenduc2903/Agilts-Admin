<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'province' => [
                'required',
                'string',
                'max:50',
            ],
            'district' => [
                'required',
                'string',
                'max:50',
            ],
            'ward' => [
                'required',
                'string',
                'max:50',
            ],
            'address_detail' => [
                'required',
                'string',
                'max:100',
            ],
        ];
    }
}
