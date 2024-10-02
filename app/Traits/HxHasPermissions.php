<?php
namespace App\Traits;

use App\Models\Permission;
use App\Models\Module;

trait HxHasPermissions
{
    public function modulePermissions()
    {
        return $this->belongsToMany(Permission::class, $this->permissionPivotTable())
                    ->withPivot('module_id');
    }

    abstract public function permissionPivotTable();

    public function attachPermission($moduleId, $permissionId) {
        $existing = $this->modulePermissions()
            ->wherePivot('module_id', $moduleId)
            ->wherePivot('permission_id', $permissionId)
            ->exists();

        if (!$existing) {
            $this->modulePermissions()->attach($permissionId, ['module_id' => $moduleId]);
        }
    }

    public function addPermission($module, $permission)
    {
        $moduleId = is_numeric($module) ? $module : Module::where('name', $module)->first()->id;
        $permissionId = is_numeric($permission) ? $permission : Permission::where('name', $permission)->first()->id;
        
        $this->attachPermission($moduleId, $permissionId);
    }

    public function addPermissions($module, $permissions)
    {
        $moduleId = is_numeric($module) ? $module : Module::where('name', $module)->first()->id;

        $permissionIds = array_map(function ($permission) {
            return is_numeric($permission) ? $permission : Permission::where('name', $permission)->first()->id;
        }, $permissions);

        foreach ($permissionIds as $permissionId) {
            $this->attachPermission($moduleId, $permissionId);
        }
    }

    public function removePermission($module, $permission)
    {
        $moduleId = is_numeric($module) ? $module : Module::where('name', $module)->first()->id;
        $permissionId = is_numeric($permission) ? $permission : Permission::where('name', $permission)->first()->id;

        $this->modulePermissions()->wherePivot('module_id', $moduleId)->detach($permissionId);
    }

    public function removePermissions($module, $permissions)
    {
        $moduleId = is_numeric($module) ? $module : Module::where('name', $module)->first()->id;

        $permissionIds = array_map(function ($permission) {
            return is_numeric($permission) ? $permission : Permission::where('name', $permission)->first()->id;
        }, $permissions);

        $this->modulePermissions()->wherePivot('module_id', $moduleId)->detach($permissionIds);
    }

    public function addAllPermission($module = null)
    {
        $modules = $module ? [$module] : Module::where('is_active', true)->get();

        foreach ($modules as $mod) {
            $permissions = Permission::all();
            $this->addPermissions($mod->id, $permissions->pluck('id')->toArray());
        }
    }

    public function removeAllPermission($module = null)
    {
        if ($module) {
            // $moduleId = is_numeric($module) ? $module : Module::where('name', $module)->first()->id;
            $this->modulePermissions()->wherePivot('module_id', $module->id)->detach();
        } else {
            $this->modulePermissions()->detach();
        }
    }
}