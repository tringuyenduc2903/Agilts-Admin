<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class RoleCrudController
 * @package App\Http\Controllers
 * @property-read CrudPanel $crud
 */
class RoleCrudController extends \Backpack\PermissionManager\app\Http\Controllers\RoleCrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        parent::setup();

        denyAllAccess(Permission::ROLE_CRUD);
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    public function setupListOperation()
    {
        parent::setupListOperation();

        CRUD::column('users_count')
            ->wrapper([
                'href' => fn($crud, $column, $entry): string => backpack_url("admin?role={$entry->getKey()}"),
            ]);
    }
}
