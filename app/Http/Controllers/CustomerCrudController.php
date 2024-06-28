<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Enums\Identification;
use App\Enums\Permission;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Backpack\Pro\Http\Controllers\Operations\BulkTrashOperation;
use Backpack\Pro\Http\Controllers\Operations\TrashOperation;

/**
 * Class CustomerCrudController
 * @package App\Http\Controllers
 * @property-read CrudPanel $crud
 */
class CustomerCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use ShowOperation;
    use TrashOperation;
    use BulkTrashOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Customer::class);
        CRUD::setEntityNameStrings(trans('Customer'), trans('Customers'));
        CRUD::setRoute(backpack_url('customer'));

        denyAllAccess(Permission::CUSTOMER_CRUD);
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    public function setupListOperation()
    {
        CRUD::column('name')
            ->label(trans('backpack::permissionmanager.name'));
        CRUD::column('email')
            ->label(trans('backpack::permissionmanager.email'))
            ->type('email');
        CRUD::column('phone_number')
            ->label(trans('Phone number'))
            ->type('phone');
        CRUD::column('birthday')
            ->label(trans('Birthday'));
        CRUD::column('gender')
            ->label(trans('Gender'))
            ->type('select2_from_array')
            ->options(Gender::map());
        CRUD::filter('name')
            ->label(trans('backpack::permissionmanager.name'))
            ->type('text');
        CRUD::filter('email')
            ->label(trans('backpack::permissionmanager.email'))
            ->type('text');
        CRUD::filter('phone_number')
            ->label(trans('Phone number'))
            ->type('text');
        CRUD::filter('birthday')
            ->label(trans('Birthday'))
            ->type('date');
        CRUD::filter('gender')
            ->label(trans('Gender'))
            ->type('select2')
            ->values(Gender::map());
    }

    /**
     * @return void
     */
    public function setupShowOperation()
    {
        CRUD::column('name')
            ->label(trans('backpack::permissionmanager.name'));
        CRUD::column('email')
            ->label(trans('backpack::permissionmanager.email'))
            ->type('email');
        CRUD::column('email_verified_at')
            ->label(trans('Email verified at'));
        CRUD::column('phone_number')
            ->label(trans('Phone number'))
            ->type('phone');
        CRUD::column('phone_number_verified_at')
            ->label(trans('Phone number verified at'));
        CRUD::column('birthday')
            ->label(trans('Birthday'));
        CRUD::column('gender')
            ->label(trans('Gender'))
            ->type('select2_from_array')
            ->options(Gender::map());
        CRUD::column('timezone')
            ->label(trans('Timezone'))
            ->type('select2_from_array')
            ->options(array_combine(
                timezone_identifiers_list(),
                timezone_identifiers_list()
            ));
        CRUD::column('addresses')
            ->label(trans('Addresses'))
            ->type('repeatable')
            ->subfields($this->addressesSubfields(\App\Enums\Address\Customer::values()));
        CRUD::column('identifications')
            ->label(trans('Identifications'))
            ->type('repeatable')
            ->subfields($this->identificationsSubfields());

        // if the model has timestamps, add columns for created_at and updated_at
        if (CRUD::get('show.timestamps') && CRUD::getModel()->usesTimestamps()) {
            CRUD::column(CRUD::getModel()->getCreatedAtColumn())
                ->label(trans('Created at'));
            CRUD::column(CRUD::getModel()->getUpdatedAtColumn())
                ->label(trans('Updated at'));
        }
    }

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

    /**
     * @return array[]
     */
    protected function identificationsSubfields(): array
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
            'options' => Identification::values(),
            'allows_null' => false,
        ], [
            'name' => 'number',
            'label' => trans('Number'),
            'type' => 'text',
        ], [
            'name' => 'issued_name',
            'label' => trans('Issued name'),
            'type' => 'text',
        ], [
            'name' => 'issuance_date',
            'label' => trans('Issued date'),
            'type' => 'date',
            'wrapper' => [
                'class' => 'form-group col-sm-12 col-md-6 mb-3',
            ],
        ], [
            'name' => 'expiry_date',
            'label' => trans('Expiry date'),
            'type' => 'date',
            'wrapper' => [
                'class' => 'form-group col-sm-12 col-md-6 mb-3',
            ],
        ]];
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    public function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    public function setupCreateOperation()
    {
        Widget::add()
            ->type('script')
            ->content(resource_path('assets/js/admin/forms/address.js'));
        Widget::add()
            ->type('script')
            ->content(resource_path('assets/js/admin/forms/identifications.js'));

        CRUD::setValidation(CustomerRequest::class);
        CRUD::field('name')
            ->label(trans('backpack::permissionmanager.name'));
        CRUD::field('email')
            ->label(trans('backpack::permissionmanager.email'))
            ->type('email');
        CRUD::field('phone_number')
            ->label(trans('Phone number'))
            ->type('phone');
        CRUD::field('birthday')
            ->label(trans('Birthday'));
        CRUD::field('gender')
            ->label(trans('Gender'))
            ->type('select2_from_array')
            ->options(Gender::map());
        CRUD::field('timezone')
            ->label(trans('Timezone'))
            ->type('select2_from_array')
            ->options(array_combine(
                timezone_identifiers_list(),
                timezone_identifiers_list()
            ));
        CRUD::field('addresses')
            ->label(trans('Addresses'))
            ->type('repeatable')
            ->subfields($this->addressesSubfields(\App\Enums\Address\Customer::values()));
        CRUD::field('identifications')
            ->label(trans('Identifications'))
            ->type('repeatable')
            ->subfields($this->identificationsSubfields());
    }
}
