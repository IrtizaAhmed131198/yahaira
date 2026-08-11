<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'setter']);
        Role::create(['name' => 'closer']);
        Role::create(['name' => 'matchmaker']);
        Role::create(['name' => 'coach']);
        Role::create(['name' => 'billing']);
    }
}
