<?php

namespace App\Trait\Controllers;

trait AddressesFieldTrait
{
    /**
     * @param array $options
     * @return array[]
     */
    protected function addressesSubfields(array $options): array
    {
        return [[
            'name' => 'default',
            'label' => trans('Set as default'),
            'type' => 'switch',
            'wrapper' => [
                'class' => 'form-group col-sm-12 d-flex justify-content-end',
            ],
        ], [
            'name' => 'type',
            'label' => trans('Type'),
            'type' => 'select2_from_array',
            'options' => $options,
            'allows_null' => false,
        ], [
            'name' => 'country',
            'label' => trans('Country'),
            'type' => 'text',
            'default' => 'Việt Nam',
            'wrapper' => [
                'class' => 'form-group col-sm-12 col-md-6 mb-3',
            ],
        ], [
            'name' => 'province',
            'label' => trans('Province'),
            'type' => 'text',
            'wrapper' => [
                'class' => 'form-group col-sm-12 col-md-6',
            ],
        ], [
            'name' => 'district',
            'label' => trans('District'),
            'type' => 'text',
            'wrapper' => [
                'class' => 'form-group col-sm-12 col-md-6',
            ],
        ], [
            'name' => 'ward',
            'label' => trans('Ward'),
            'type' => 'text',
            'wrapper' => [
                'class' => 'form-group col-sm-12 col-md-6',
            ],
        ], [
            'name' => 'address_detail',
            'label' => trans('Address detail'),
            'type' => 'textarea',
        ]];
    }
}
