<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         //Users
         Permission::create(['guard_name' => 'api', 'name' => 'Register user']);
         Permission::create(['guard_name' => 'api', 'name' => 'Show users']);
         Permission::create(['guard_name' => 'api', 'name' => 'Edit user']);
         Permission::create(['guard_name' => 'api', 'name' => 'Delete user']);
 
         //Projects
         Permission::create(['guard_name' => 'api', 'name' => 'Register project']);
         Permission::create(['guard_name' => 'api', 'name' => 'Show projects']);
         Permission::create(['guard_name' => 'api', 'name' => 'Edit project']);
         Permission::create(['guard_name' => 'api', 'name' => 'Delete project']);
 
         //Clients
         Permission::create(['guard_name' => 'api', 'name' => 'Register client']);
         Permission::create(['guard_name' => 'api', 'name' => 'Show clients']);
         Permission::create(['guard_name' => 'api', 'name' => 'Edit client']);
         Permission::create(['guard_name' => 'api', 'name' => 'Delete client']);
 
         //api
         Permission::create(['guard_name' => 'api', 'name' => 'Register payments']);
         Permission::create(['guard_name' => 'api', 'name' => 'Show reports']);
    }
}
