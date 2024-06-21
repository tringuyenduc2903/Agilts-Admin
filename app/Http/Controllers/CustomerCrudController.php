<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
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

        // if the model has timestamps, add columns for created_at and updated_at
        if (CRUD::get('show.timestamps') && CRUD::getModel()->usesTimestamps()) {
            CRUD::column(CRUD::getModel()->getCreatedAtColumn())
                ->label(trans('Created at'));
            CRUD::column(CRUD::getModel()->getUpdatedAtColumn())
                ->label(trans('Updated at'));
        }
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
    }
}
