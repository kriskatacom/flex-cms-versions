<?php

namespace Flex\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    protected $table = 'users';

    protected ?array $permissionsCache = null;

    protected $fillable = [
        'username',
        'email',
        'password',
        'options',
        'last_login'
    ];

    protected $casts = [
        'options' => AsArrayObject::class,
        'last_login' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_BCRYPT);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function can(string $permissionSlug): bool
    {
        if ($this->hasRole('admin')) {
            return true;
        }

        return in_array($permissionSlug, $this->getPermissions());
    }

    public function hasRole(string $roleSlug): bool
    {
        return $this->roles->contains('slug', $roleSlug);
    }

    public function getPermissions(): array
    {
        if ($this->permissionsCache !== null) {
            return $this->permissionsCache;
        }

        $result = \Illuminate\Support\Facades\DB::select("
        SELECT DISTINCT p.slug 
        FROM permissions p
        JOIN role_permission rp ON p.id = rp.permission_id
        JOIN user_role ur ON rp.role_id = ur.role_id
        WHERE ur.user_id = ?
    ", [$this->id]);
        $this->permissionsCache = array_map(fn($item) => $item->slug, $result);
        return $this->permissionsCache;
    }
}