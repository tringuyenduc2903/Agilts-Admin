<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use App\Http\Requests\BranchRequest;
use App\Models\Branch;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Backpack\Pro\Http\Controllers\Operations\BulkTrashOperation;
use Backpack\Pro\Http\Controllers\Operations\InlineCreateOperation;
use Backpack\Pro\Http\Controllers\Operations\TrashOperation;

/**
 * Class BranchCrudController
 * @package App\Http\Controllers
 * @property-read CrudPanel $crud
 */
class BranchCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use ShowOperation;
    use TrashOperation;
    use BulkTrashOperation;
    use InlineCreateOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(Branch::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/branch');
        CRUD::setEntityNameStrings(trans('Branch'), trans('Branches'));

        denyAllAccess(Permission::BRANCH_CRUD);
    }

    /**
     * @return void
     */
    public function setupShowOperation()
    {
        $this->setupListOperation();

        CRUD::column('addresses')
            ->label(trans('Addresses'))
            ->type('repeatable')
            ->subfields($this->subfields());

        CRUD::column('users')
            ->label(trans('backpack::permissionmanager.users'));

        // if the model has timestamps, add columns for created_at and updated_at
        if (CRUD::get('show.timestamps') && CRUD::getModel()->usesTimestamps()) {
            CRUD::column(CRUD::getModel()->getCreatedAtColumn())
                ->label(trans('Created at'));
            CRUD::column(CRUD::getModel()->getUpdatedAtColumn())
                ->label(trans('Updated at'));
        }
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name')
            ->label(trans('Name'));

        CRUD::filter('name')
            ->label(trans('Name'))
            ->type('text');
    }

    /**
     * @return array[]
     */
    protected function subfields(): array
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
            'options' => \App\Enums\Address\Branch::values(),
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
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        Widget::add()
            ->type('script')
            ->content(resource_path('assets/js/admin/forms/branch.js'));

        CRUD::setValidation(BranchRequest::class);
        CRUD::field('name')
            ->label(trans('Name'));
        CRUD::field('addresses')
            ->label(trans('Addresses'))
            ->type('repeatable')
            ->subfields($this->subfields());
    }
}
