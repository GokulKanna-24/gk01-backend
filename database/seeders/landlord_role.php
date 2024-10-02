<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class landlord_role extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'superAdmin',
                'display_name' => 'Super Admin',
                'description' => 'Full access to all resources',
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Administrative access to certain modules',
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Access to manage specific resources and modules',
            ],
            [
                'name' => 'teamlead',
                'display_name' => 'Team Lead',
                'description' => 'Access to manage specific resources and modules',
            ],
            [
                'name' => 'user',
                'display_name' => 'User',
                'description' => 'Basic access to view and interact with the system',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
