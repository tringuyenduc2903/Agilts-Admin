<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use App\Enums\ProductDetailStatus;
use App\Http\Requests\InventoryRequest;
use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductOption;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\Pro\Http\Controllers\Operations\FetchOperation;

/**
 * Class InventoryCrudController
 * @package App\Http\Controllers
 * @property-read CrudPanel $crud
 */
class InventoryCrudController extends CrudController
{
    use ListOperation;
    use UpdateOperation;
    use ShowOperation;
    use FetchOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(ProductOption::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/inventory');
        CRUD::setEntityNameStrings(trans('Inventory'), trans('Inventory'));

        denyAllAccess(Permission::INVENTORY_CRUD);
    }

    /**
     * @return void
     */
    public function setupShowOperation()
    {
        $this->setupListOperation();

        /** @var ProductOption $option */
        $option = CRUD::getCurrentEntry();

        CRUD::column('stock')
            ->label(trans('Number of products in stock'))
            ->type('number')
            ->value($option->details()
                ->where('status', ProductDetailStatus::STORAGE)
                ->count());
        CRUD::column('sold')
            ->label(trans('Number of products sold'))
            ->type('number')
            ->value($option->details()
                ->where('status', ProductDetailStatus::SOLD)
                ->count());
        CRUD::column('details')
            ->label(trans('Details'))
            ->type('repeatable')
            ->subfields($this->detailsSubfields());

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
        CRUD::column('product')
            ->label(trans('Product'));
        CRUD::column('sku')
            ->label(trans('SKU'));
        CRUD::column('price')
            ->label(trans('Price'))
            ->type('number')
            ->prefix('VND' . ' ');
        CRUD::column('model_name')
            ->label(trans('Model name'));
        CRUD::filter('product_id')
            ->label(trans('Product'))
            ->type('select2_ajax')
            ->values(backpack_url('inventory/fetch/product'))
            ->method('POST');
        CRUD::filter('sku')
            ->label(trans('SKU'))
            ->type('text');
        CRUD::filter('price')
            ->label(trans('Price'))
            ->type('range')
            ->whenActive(function ($value) {
                $range = json_decode($value);

                if ($range->from)
                    CRUD::addClause('where', 'price', '>=', (float)$range->from);

                if ($range->to)
                    CRUD::addClause('where', 'price', '<=', (float)$range->to);
            });
        CRUD::filter('model_name')
            ->label(trans('Model name'))
            ->type('text');
    }

    /**
     * @return array[]
     */
    protected function detailsSubfields(): array
    {
        return [[
            'name' => 'chassis_number',
            'label' => trans('Chassis number'),
            'type' => 'text',
            'wrapper' => [
                'class' => 'form-group col-sm-12 col-md-6',
            ],
        ], [
            'name' => 'engine_number',
            'label' => trans('Engine number'),
            'type' => 'text',
            'wrapper' => [
                'class' => 'form-group col-sm-12 col-md-6',
            ],
        ], [
            'name' => 'status',
            'label' => trans('Status'),
            'type' => 'select2_from_array',
            'options' => ProductDetailStatus::values(),
            'allows_null' => false,
        ], [
            'name' => 'branch',
            'label' => trans('Branch'),
            'type' => 'relationship',
            'relation_type' => 'BelongsTo',
            'model' => Branch::class,
            'entity' => 'branch',
            'inline_create' => true,
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
        CRUD::setValidation(InventoryRequest::class);

        CRUD::field('details')
            ->label(trans('Details'))
            ->type('repeatable')
            ->subfields($this->detailsSubfields());
    }

    protected function fetchBranch()
    {
        return $this->fetch(Branch::class);
    }

    protected function fetchProduct()
    {
        return $this->fetch(Product::class);
    }
}
