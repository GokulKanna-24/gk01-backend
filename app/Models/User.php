<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Traits\HxHasPermissions;

class User extends Authenticatable
{
    use HxHasPermissions, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'username',
        'email',
        'uuid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'is_active',
        'delete_flag',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function permissionPivotTable()
    {
        return 'user_module_permission'; // Pivot table for user
    }

    public function addRole($role)
    {
        $roleId = is_numeric($role) ? $role : Role::where('name', $role)->first()->id;
        // $this->roles()->sync([$roleId], false); // it will add multiple roles in same user
        $this->roles()->sync([$roleId]); 
    }

    public function removeRole()
    {
        $this->roles()->detach();
    }
}
