<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class common_permission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'view',
                'display_name' => 'View',
                'description' => 'Allows viewing of content or resources',
            ],
            [
                'name' => 'create',
                'display_name' => 'Create',
                'description' => 'Allows creating new content or resources',
            ],
            [
                'name' => 'update',
                'display_name' => 'Update',
                'description' => 'Allows editing or updating content or resources',
            ],
            [
                'name' => 'delete',
                'display_name' => 'Delete',
                'description' => 'Allows deletion of content or resources',
            ],
            [
                'name' => 'upload',
                'display_name' => 'Upload',
                'description' => 'Allows uploading files or documents',
            ],
            [
                'name' => 'download',
                'display_name' => 'Download',
                'description' => 'Allows downloading files or documents',
            ],
            [
                'name' => 'lock',
                'display_name' => 'Lock',
                'description' => 'Allows locking resources to prevent modifications',
            ],
            [
                'name' => 'unlock',
                'display_name' => 'Unlock',
                'description' => 'Allows unlocking resources for modifications',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
