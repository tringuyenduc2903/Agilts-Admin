<?php

namespace App\Http\Requests;

use App\Enums\ProductDetailStatus;
use App\Models\Branch;
use App\Models\ProductDetail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InventoryRequest extends FormRequest
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
            'details' => [
                'required',
                'array',
            ],
            'details.*.chassis_number' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $id = request()->input(
                        str_replace('.chassis_number', '.id', $attribute)
                    );

                    $validator = Validator::make([
                        'chassis_number' => $value,
                    ], [
                        'chassis_number' => Rule::unique(ProductDetail::class)->ignore($id),
                    ]);

                    if ($validator->fails())
                        $fail($validator->errors()->first('chassis_number'));
                },
            ],
            'details.*.engine_number' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $id = request()->input(
                        str_replace('.engine_number', '.id', $attribute)
                    );

                    $validator = Validator::make([
                        'engine_number' => $value,
                    ], [
                        'engine_number' => Rule::unique(ProductDetail::class)->ignore($id),
                    ]);

                    if ($validator->fails())
                        $fail($validator->errors()->first('engine_number'));
                },
            ],
            'details.*.status' => [
                'required',
                'integer',
                Rule::in(ProductDetailStatus::keys()),
            ],
            'details.*.branch' => [
                'required',
                'integer',
                Rule::exists(Branch::class, 'id'),
            ],
        ];
    }
}
