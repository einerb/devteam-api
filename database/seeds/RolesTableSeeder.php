<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Role::create(['guard_name' => 'api', 'name' => 'admin'])->givePermissionTo(['Register user', 'Show users', 'Edit user', 'Delete user', 'Register project', 'Show projects', 'Edit project', 'Delete project', 'Register client', 'Show clients', 'Edit client', 'Delete client', 'Register payments', 'Show reports']);
        Role::create(['guard_name' => 'api', 'name' => 'operator'])->givePermissionTo(['Register user', 'Show users', 'Edit user', 'Delete user', 'Register client', 'Show clients', 'Edit client', 'Delete client']);
        Role::create(['guard_name' => 'api', 'name' => 'publisher'])->givePermissionTo(['Register project', 'Show projects', 'Edit project', 'Delete project']);
    }
}
