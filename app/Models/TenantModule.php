<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantModule extends Model
{
    use HasFactory;

    protected $connection = 'mysql';  // Use landlord database connection
    protected $fillable = [
                'name', 'display_name', 'description', 'route_name', 'icon', 
                'is_active', 'delete_flag'
            ];

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'tenant_module_permission', 'tenant_module_id', 'permission_id');
    }

    public function addPermission($permission)
    {
        $permissionId = is_numeric($permission) ? $permission : Permission::where('name', $permission)->first()->id;
        $this->permissions()->syncWithoutDetaching([$permissionId]);
    }

    public function removePermission($permission)
    {
        $permissionId = is_numeric($permission) ? $permission : Permission::where('name', $permission)->first()->id;
        $this->permissions()->detach($permissionId);
    }

    public function addAllPermission()
    {
        $permissions = Permission::all();
        $this->permissions()->syncWithoutDetaching($permissions->pluck('id')->toArray());
    }

    public function removeAllPermission()
    {
        $this->permissions()->detach();
    }
}
