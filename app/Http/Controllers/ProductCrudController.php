<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Enums\ProductVisibility;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
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
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers
 * @property-read CrudPanel $crud
 */
class ProductCrudController extends CrudController
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
        CRUD::setModel(Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings(trans('Product'), trans('Products'));

        denyAllAccess(Permission::PRODUCT_CRUD);
    }

    /**
     * @return void
     */
    public function setupShowOperation()
    {
        $this->setupListOperation();

        CRUD::column('description')
            ->label(trans('Description'))
            ->type('tinymce')
            ->after('name')
            ->tab(trans('Basic information'));
        CRUD::column('specifications')
            ->label(trans('Specifications'))
            ->type('repeatable')
            ->subfields($this->speciationsSubfields())
            ->tab(trans('Basic information'));
        CRUD::column('options')
            ->label(trans('Options'))
            ->type('repeatable')
            ->subfields($this->optionsSubfields())
            ->tab(trans('Options'));

        // if the model has timestamps, add columns for created_at and updated_at
        if (CRUD::get('show.timestamps') && CRUD::getModel()->usesTimestamps()) {
            CRUD::column(CRUD::getModel()->getCreatedAtColumn())
                ->label(trans('Created at'))
                ->tab(trans('Basic information'));
            CRUD::column(CRUD::getModel()->getUpdatedAtColumn())
                ->label(trans('Updated at'))
                ->tab(trans('Basic information'));
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
            ->label(trans('Name'))
            ->tab(trans('Basic information'));
        CRUD::column('enabled')
            ->label(trans('Enable'))
            ->type('switch')
            ->default(true)
            ->tab(trans('Basic information'));
        CRUD::column('visibility')
            ->label(trans('Visibility'))
            ->type('select2_from_array')
            ->options(ProductVisibility::values())
            ->tab(trans('Basic information'));
        CRUD::column('status')
            ->label(trans('Status'))
            ->type('select2_from_array')
            ->options(ProductStatus::values())
            ->tab(trans('Basic information'));
        CRUD::column('type')
            ->label(trans('Type'))
            ->type('select2_from_array')
            ->options(ProductType::values())
            ->tab(trans('Basic information'));
        CRUD::filter('name')
            ->type('text')
            ->label(trans('Name'));
        CRUD::filter('disabled')
            ->label(trans('Disabled'))
            ->type('simple')
            ->whenActive(fn() => CRUD::addClause('where', 'enabled', false))
            ->whenInactive(fn() => CRUD::addClause('where', 'enabled', true));
        CRUD::filter('visibility')
            ->label(trans('Visibility'))
            ->type('select2')
            ->options(ProductVisibility::values());
        CRUD::filter('status')
            ->label(trans('Status'))
            ->type('select2')
            ->options(ProductStatus::values());
        CRUD::filter('type')
            ->label(trans('Type'))
            ->type('select2')
            ->options(ProductType::values());
    }

    /**
     * @return array[]
     */
    protected function speciationsSubfields(): array
    {
        return [[
            'name' => 'key',
            'label' => trans('Key'),
            'type' => 'text',
            'wrapper' => [
                'class' => 'form-group col-sm-12 col-md-5',
            ],
        ], [
            'name' => 'value',
            'label' => trans('Value'),
            'type' => 'textarea',
            'wrapper' => [
                'class' => 'form-group col-sm-12 col-md-7',
            ],
        ]];
    }

    /**
     * @return array[]
     */
    protected function optionsSubfields(): array
    {
        return [[
            'name' => 'sku',
            'label' => trans('SKU'),
            'type' => 'text',
        ], [
            'name' => 'price',
            'label' => trans('Price'),
            'type' => 'number',
            'prefix' => 'VND' . ' ',
            'default' => 0,
        ], [
            'name' => 'price_preview',
            'label' => trans('Price preview'),
            'type' => 'text',
            'attributes' => [
                'disabled' => 'disabled',
            ],
        ], [
            'name' => 'color',
            'label' => trans('Color'),
            'type' => 'text',
        ], [
            'name' => 'model_name',
            'label' => trans('Model name'),
            'type' => 'text',
        ], [
            'name' => 'specifications',
            'label' => trans('Specifications'),
            'type' => 'table',
            'columns' => [
                'key' => trans('Key'),
                'value' => trans('Value'),
            ],
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
            ->content(resource_path('assets/js/admin/forms/price.js'));

        CRUD::setValidation(ProductRequest::class);

        // Basic information
        CRUD::field('enabled')
            ->label(trans('Enable'))
            ->type('switch')
            ->default(true)
            ->tab(trans('Basic information'));
        CRUD::field('name')
            ->label(trans('Name'))
            ->tab(trans('Basic information'));
        CRUD::field('description')
            ->label(trans('Description'))
            ->type('tinymce')
            ->tab(trans('Basic information'));
        CRUD::field('categories')
            ->label(trans('Categories'))
            ->tab(trans('Basic information'));
        CRUD::field('visibility')
            ->label(trans('Visibility'))
            ->type('select2_from_array')
            ->options(ProductVisibility::values())
            ->default(ProductVisibility::CATALOG_AND_SEARCH)
            ->tab(trans('Basic information'));
        CRUD::field('status')
            ->label(trans('Status'))
            ->type('select2_from_array')
            ->options(ProductStatus::values())
            ->tab(trans('Basic information'));
        CRUD::field('type')
            ->label(trans('Type'))
            ->type('select2_from_array')
            ->options(ProductType::values())
            ->tab(trans('Basic information'));
        CRUD::field('specifications')
            ->label(trans('Specifications'))
            ->type('repeatable')
            ->subfields($this->speciationsSubfields())
            ->tab(trans('Basic information'));

        // Options
        CRUD::field('options')
            ->label(trans('Options'))
            ->type('repeatable')
            ->subfields($this->optionsSubfields())
            ->reorder(false)
            ->tab(trans('Options'));
    }
}
