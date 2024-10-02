<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HxHasPermissions;

class Role extends Model
{
    use HxHasPermissions, HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    public function permissionPivotTable()
    {
        return 'role_module_permission'; // Pivot table for role
    }
}
