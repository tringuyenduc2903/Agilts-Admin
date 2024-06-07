<?php

namespace Database\Seeders;

use App\Models\Admin;
use Backpack\PermissionManager\app\Models\Permission;
use Backpack\PermissionManager\app\Models\Role;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->permission();
        $role = $this->role();
        $this->admin($role);
    }

    /**
     * @return void
     */
    protected function permission(): void
    {
        foreach (\App\Enums\Permission::keys() as $permission)
            Permission::upsert(
                [
                    'name' => $permission,
                    'guard_name' => config('backpack.base.guard'),
                ],
                'name'
            );
    }

    /**
     * @return Role
     */
    protected function role(): Role
    {
        $role = Role::createOrFirst([
            'name' => 'admin'
        ], [
            'guard_name' => config('backpack.base.guard'),
        ]);

        $role->givePermissionTo(Permission::all());

        return $role;
    }

    /**
     * @param Role $role
     * @return void
     */
    protected function admin(Role $role): void
    {
        $admin = Admin::createOrFirst([
            'email' => 'admin@admin.com'
        ], [
            'name' => 'Admin',
            'password' => 'admin',
        ]);

        $admin->markEmailAsVerified();

        $admin->assignRole($role);
    }
}
