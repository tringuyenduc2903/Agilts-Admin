<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;

/**
 * Class PermissionCrudController
 * @package App\Http\Controllers
 * @property-read CrudPanel $crud
 */
class PermissionCrudController extends \Backpack\PermissionManager\app\Http\Controllers\PermissionCrudController
{
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        parent::setup();

        denyAllAccess(Permission::PERMISSION_CRUD);
    }
}
