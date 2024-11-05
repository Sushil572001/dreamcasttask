<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // RolesName

        $role_name = [
            ['role_name' => 'user'],
            ['role_name' => 'manager'],
            ['role_name' => 'team leader']
        ];

        foreach ($role_name as $data) {
            Role::firstOrcreate([
                'role_name' => $data['role_name'],
            ], $data);
        }
    }
}
