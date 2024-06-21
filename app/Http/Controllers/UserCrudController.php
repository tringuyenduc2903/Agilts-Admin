<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use App\Models\Branch;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\Pro\Http\Controllers\Operations\BulkTrashOperation;
use Backpack\Pro\Http\Controllers\Operations\FetchOperation;
use Backpack\Pro\Http\Controllers\Operations\TrashOperation;
use Illuminate\Validation\Rule;

/**
 * Class UserCrudController
 * @package App\Http\Controllers
 * @property-read CrudPanel $crud
 */
class UserCrudController extends \Backpack\PermissionManager\app\Http\Controllers\UserCrudController
{
    use TrashOperation;
    use BulkTrashOperation;
    use FetchOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        parent::setup();

        CRUD::setRoute(backpack_url('admin'));
        CRUD::operation(['show', 'list'], fn() => CRUD::removeButton('delete'));

        denyAllAccess(Permission::ADMIN_CRUD);
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
        CRUD::column('branch')
            ->label(trans('Branch'));
        CRUD::column('roles_permissions')
            ->label(trans('backpack::permissionmanager.user_role_permission'))
            ->type('checklist_dependency')
            ->subfields([
                'primary' => [
                    'label' => trans('backpack::permissionmanager.role'),
                    'name' => 'roles', // the method that defines the relationship in your Model
                    'entity' => 'roles', // the method that defines the relationship in your Model
                    'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
                    'attribute' => 'name', // foreign key attribute that is shown to user
                    'model' => config('permission.models.role'), // foreign key model
                ],
                'secondary' => [
                    'label' => mb_ucfirst(trans('backpack::permissionmanager.permission_singular')),
                    'name' => 'permissions', // the method that defines the relationship in your Model
                    'entity' => 'permissions', // the method that defines the relationship in your Model
                    'entity_primary' => 'roles', // the method that defines the relationship in your Model
                    'attribute' => 'name', // foreign key attribute that is shown to user
                    'model' => config('permission.models.permission'), // foreign key model,
                ],
            ])
            ->field_unique_name('user_role_permission');

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
    public function setupListOperation()
    {
        parent::setupListOperation();

        CRUD::column('branch')
            ->label(trans('Branch'))
            ->after('email');

        CRUD::filter('name')
            ->label(trans('backpack::permissionmanager.name'))
            ->type('text')
            ->after('trashed');
        CRUD::filter('email')
            ->label(trans('backpack::permissionmanager.email'))
            ->type('text')
            ->after('name');
        CRUD::filter('branch_id')
            ->label(trans('Branch'))
            ->type('select2_ajax')
            ->values(backpack_url('user/fetch/branch'))
            ->method('POST')
            ->after('email');
    }

    /**
     * @return void
     */
    protected function addUserFields()
    {
        CRUD::setValidation(['branch' => [
            'required',
            'integer',
            Rule::exists(Branch::class, 'id'),
        ]]);
        CRUD::field('name')
            ->label(trans('backpack::permissionmanager.name'))
            ->tab(trans('User info'));
        CRUD::field('email')
            ->label(trans('backpack::permissionmanager.email'))
            ->type('email')
            ->tab(trans('User info'));
        CRUD::field('branch')
            ->label(trans('Branch'))
            ->inline_create(true)
            ->tab(trans('User info'));
        CRUD::field('password')
            ->label(trans('backpack::permissionmanager.password'))
            ->type('password')
            ->tab(trans('User info'));
        CRUD::field('password_confirmation')
            ->label(trans('backpack::permissionmanager.password_confirmation'))
            ->type('password')
            ->tab(trans('User info'));
        CRUD::field('roles,permissions')
            ->label(trans('backpack::permissionmanager.user_role_permission'))
            ->type('checklist_dependency')
            ->subfields([
                'primary' => [
                    'label' => trans('backpack::permissionmanager.roles'),
                    'name' => 'roles', // the method that defines the relationship in your Model
                    'entity' => 'roles', // the method that defines the relationship in your Model
                    'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
                    'attribute' => 'name', // foreign key attribute that is shown to user
                    'model' => config('permission.models.role'), // foreign key model
                    'pivot' => true, // on create&update, do you need to add/delete pivot table entries?]
                    'number_columns' => 3, //can be 1,2,3,4,6
                ],
                'secondary' => [
                    'label' => mb_ucfirst(trans('backpack::permissionmanager.permission_plural')),
                    'name' => 'permissions', // the method that defines the relationship in your Model
                    'entity' => 'permissions', // the method that defines the relationship in your Model
                    'entity_primary' => 'roles', // the method that defines the relationship in your Model
                    'attribute' => 'name', // foreign key attribute that is shown to user
                    'model' => config('permission.models.permission'), // foreign key model
                    'pivot' => true, // on create&update, do you need to add/delete pivot table entries?]
                    'number_columns' => 3, //can be 1,2,3,4,6
                ],
            ])
            ->tab(trans('User role'))
            ->field_unique_name('user_role_permission');
    }

    protected function fetchBranch()
    {
        return $this->fetch(Branch::class);
    }
}
