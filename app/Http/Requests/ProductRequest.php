<?php

namespace App\Http\Requests;

use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Enums\ProductVisibility;
use App\Models\ProductOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
            'enabled' => [
                'required',
                'boolean',
            ],
            'name' => [
                'required',
                'string',
                'max:50',
            ],
            'description' => [
                'nullable',
                'string',
                'max:4294967295',
            ],
            'visibility' => [
                'required',
                'integer',
                Rule::in(ProductVisibility::keys()),
            ],
            'status' => [
                'required',
                'integer',
                Rule::in(ProductStatus::keys()),
            ],
            'type' => [
                'required',
                'integer',
                Rule::in(ProductType::keys()),
            ],
            'specifications.*.key' => [
                'required',
                'string',
                'max:50',
            ],
            'specifications.*.value' => [
                'required',
                'string',
                'max:255',
            ],
            'options' => [
                'required',
                'array',
            ],
            'options.*.sku' => [
                'required',
                'string',
                'max:50',
                function ($attribute, $value, $fail) {
                    $id = request()->input(
                        str_replace('.sku', '.id', $attribute)
                    );

                    $validator = Validator::make([
                        'sku' => $value,
                    ], [
                        'sku' => Rule::unique(ProductOption::class)->ignore($id),
                    ]);

                    if ($validator->fails())
                        $fail($validator->errors()->first('sku'));
                },
            ],
            'options.*.price' => [
                'required',
                'decimal:0,2',
            ],
            'options.*.color' => [
                'required',
                'string',
                'max:50',
            ],
            'options.*.model_name' => [
                'required',
                'string',
                'max:50',
            ],
        ];
    }
}
