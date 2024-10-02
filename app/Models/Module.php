<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant; // Tenancy trait for tenant models
use Stancl\Tenancy\Facades\Tenancy; // To check tenancy context

class Module extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table = 'modules'; // Common table name for both tenant and landlord

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Check if you're working with the tenant or landlord database
        if (tenancy()->initialized) {
            // Tenant DB columns (id, uuid)
            $this->fillable = ['uuid'];
        } else {
            // Landlord DB columns (id, name, display_name, description)
            $this->fillable = [
                'name', 'display_name', 'description', 'route_name', 'icon', 
                'is_active', 'delete_flag'
            ];
        }
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'module_permission');
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
