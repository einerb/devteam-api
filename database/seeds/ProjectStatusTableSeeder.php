<?php

use Illuminate\Database\Seeder;
use App\ProjectStatus;

class ProjectStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProjectStatus::create(['name' => 'creado', 'description' => 'Proyecto creado']);
        ProjectStatus::create(['name' => 'progreso', 'description' => 'Proyecto en progreso']);
        ProjectStatus::create(['name' => 'finalizado', 'description' => 'Proyecto finalizado']);
        ProjectStatus::create(['name' => 'cancelado', 'description' => 'Proyecto cancelado']);
    }
}
