<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'identification' => 1045733370,
            'name' => 'Einer',
            'lastname' => 'Bravo Cárdenas',
            'email' => 'einer.bravo@devteam.com.co',
            'password' => bcrypt('neutro123*devteam'),
            'phone' => '3232904614'
        ]);

        $user->assignRole('admin');
    }
}
