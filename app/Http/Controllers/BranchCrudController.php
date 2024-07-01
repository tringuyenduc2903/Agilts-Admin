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

        CRUD::column('address.address_detail')
            ->label(trans('Address detail'));

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
        CRUD::column('phone_number')
            ->label(trans('Phone number'))
            ->type('phone');
        CRUD::column('address.country')
            ->label(trans('Country'));
        CRUD::column('address.province')
            ->label(trans('Province'));
        CRUD::column('address.district')
            ->label(trans('District'));
        CRUD::column('address.ward')
            ->label(trans('Ward'));
        CRUD::column('users')
            ->label(trans('backpack::permissionmanager.users'))
            ->type('relationship_count');
        CRUD::column('details')
            ->label(trans('Product'))
            ->type('relationship_count');

        CRUD::filter('name')
            ->label(trans('Name'))
            ->type('text');
        CRUD::filter('phone_number')
            ->label(trans('Phone number'))
            ->type('text');
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
        CRUD::setValidation(BranchRequest::class);
        CRUD::field('name')
            ->label(trans('Name'));
        CRUD::field('phone_number')
            ->label(trans('Phone number'))
            ->type('text');
        CRUD::field('address.default')
            ->type('hidden')
            ->default(true);
        CRUD::field('address.type')
            ->type('hidden')
            ->default(0);
        CRUD::field('address.country')
            ->label(trans('Country'))
            ->default('Việt Nam');
        CRUD::field('address.province')
            ->label(trans('Province'));
        CRUD::field('address.district')
            ->label(trans('District'));
        CRUD::field('address.ward')
            ->label(trans('Ward'));
        CRUD::field('address.address_detail')
            ->label(trans('Address detail'))
            ->type('textarea');
    }
}
