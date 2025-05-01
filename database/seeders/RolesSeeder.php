<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);
        Role::firstOrCreate([
            'name' => 'customer',
            'guard_name' => 'web'
        ]);
        Role::firstOrCreate([
            'name' => 'account executive',
            'guard_name' => 'web'
        ]);
    }
}
