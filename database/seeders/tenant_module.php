<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TenantModule;
use App\Models\Module;

class tenant_module extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all the tenant_modules from the landlord DB
        $landlordModules = TenantModule::all();

        foreach ($landlordModules as $landlordModule) {
            Module::create([
                'uuid' => $landlordModule->id,  // Using landlord's tenant_module.id as uuid
            ]);
        }
    }
}
