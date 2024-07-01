<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;

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
}
