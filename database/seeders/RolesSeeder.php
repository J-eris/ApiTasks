<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create roles and assign created permissions
        $adminRole = Role::create(['name' => 'admin']);
        $developerRole = Role::create(['name' => 'developer']);
        $clientRole = Role::create(['name' => 'client']);
        $freelancerRole = Role::create(['name' => 'freelancer']);

        // Create permissions roles
        $permission_create_roles = Permission::create(['name' => 'create roles']);
        $permission_view_roles = Permission::create(['name' => 'view roles']);
        $permission_update_roles = Permission::create(['name' => 'update roles']);
        $permission_delete_roles = Permission::create(['name' => 'delete roles']);

        // Create permissions users
        $permission_create_users = Permission::create(['name' => 'create users']);
        $permission_view_users = Permission::create(['name' => 'view users']);
        $permission_update_users = Permission::create(['name' => 'update users']);
        $permission_delete_users = Permission::create(['name' => 'delete users']);

        // Create permissions categories
        $permission_create_categories = Permission::create(['name' => 'create categories']);
        $permission_view_categories = Permission::create(['name' => 'view categories']);
        $permission_update_categories = Permission::create(['name' => 'update categories']);
        $permission_delete_categories = Permission::create(['name' => 'delete categories']);

        // Asignar permisos a roles
        $permissions_admin = [
            $permission_create_roles,
            $permission_view_roles,
            $permission_update_roles,
            $permission_delete_roles,
            $permission_create_users,
            $permission_view_users,
            $permission_update_users,
            $permission_delete_users,
            $permission_create_categories,
            $permission_view_categories,
            $permission_update_categories,
            $permission_delete_categories,
        ];

        $permissions_developer = [
            $permission_create_users,
            $permission_view_users,
            $permission_update_users,
            $permission_delete_users,
            $permission_create_categories,
            $permission_view_categories,
            $permission_update_categories,
            $permission_delete_categories,
        ];

        $permissions_client = [
            $permission_view_users,
            $permission_update_users,
        ];

        $permissions_freelancer = [
            $permission_view_users,
            $permission_update_users,
        ];

        // Asigned permissions to roles
        $adminRole->syncPermissions($permissions_admin);
        $developerRole->syncPermissions($permissions_developer);
        $clientRole->syncPermissions($permissions_client);
        $freelancerRole->syncPermissions($permissions_freelancer);

    }
}
