<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TenantModule;

class landlord_tenant_module extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'name' => 'dashboard',
                'display_name' => 'Dashboard',
                'description' => 'Provides a summary view of system data and stats',
            ],
            [
                'name' => 'profile',
                'display_name' => 'Profile',
                'description' => 'Handles user profile management',
            ],
            [
                'name' => 'blog',
                'display_name' => 'Blog',
                'description' => 'Manages blog posts and articles',
            ],
            [
                'name' => 'setting',
                'display_name' => 'Settings',
                'description' => 'Handles system settings and configurations',
            ],
            [
                'name' => 'report',
                'display_name' => 'Reports',
                'description' => 'Allows users to generate and view system reports',
            ],
        ];

        foreach ($modules as $module) {
            TenantModule::create($module);
        }
    }
}
