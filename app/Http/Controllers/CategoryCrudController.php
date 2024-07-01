<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
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
 * Class CategoryCrudController
 * @package App\Http\Controllers
 * @property-read CrudPanel $crud
 */
class CategoryCrudController extends CrudController
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
        CRUD::setModel(Category::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/category');
        CRUD::setEntityNameStrings(trans('Category'), trans('Categories'));

        denyAllAccess(Permission::CATEGORY_CRUD);
    }

    /**
     * @return void
     */
    public function setupShowOperation()
    {
        $this->setupListOperation();

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
        CRUD::column('products')
            ->label(trans('Products'))
            ->type('relationship_count');

        CRUD::filter('name')
            ->label(trans('Name'))
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
        CRUD::setValidation(CategoryRequest::class);
        CRUD::field('name')
            ->label(trans('Name'));
    }
}
