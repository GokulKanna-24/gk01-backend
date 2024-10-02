<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;

class landlord_module extends Seeder
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
                'route_name' => '/dashboard',
                'icon' => 'dash',
            ],
            [
                'name' => 'profile',
                'display_name' => 'Profile',
                'description' => 'Handles user profile management',
                'route_name' => '/profile',
                'icon' => 'prof',
            ],
            [
                'name' => 'blog',
                'display_name' => 'Blog',
                'description' => 'Manages blog posts and articles',
                'route_name' => '/blog',
                'icon' => 'blog',
            ],
            [
                'name' => 'setting',
                'display_name' => 'Settings',
                'description' => 'Handles system settings and configurations',
                'route_name' => '/setting',
                'icon' => 'set',
            ],
            [
                'name' => 'report',
                'display_name' => 'Reports',
                'description' => 'Allows users to generate and view system reports',
                'route_name' => '/report',
                'icon' => 'rep',
            ],
        ];

        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}
