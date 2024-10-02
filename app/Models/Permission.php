<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    public function roles() {
        return $this->belongsToMany(Role::class, 'module_permission_role', 'permission_id', 'role_id');
    }

    public function modules() {
        return $this->belongsToMany(Module::class, 'module_permission_role', 'permission_id', 'module_id');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'module_permission_user', 'permission_id', 'user_id');
    }
}
