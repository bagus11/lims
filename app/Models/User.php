<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        // signature (base64 tanda tangan pengesah PDF) & nik hanya untuk pemakaian
        // server-side; jangan pernah terserialisasi ke response JSON.
        'signature',
        'nik',
    ];

    /**
     * hasPermissionTo() melempar PermissionDoesNotExist (HTTP 500) bila row
     * permission belum ada di DB. Pakai ini di jalur runtime.
     */
    public function hasPermissionSafe(string $permission): bool
    {
        try {
            return $this->hasPermissionTo($permission);
        } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $e) {
            return false;
        }
    }

    /**
     * Gate mutasi master data & setting approval: permission eksplisit bila
     * tersedia, fallback role admin (menus/permissions produksi di-manage manual).
     */
    public function canManageMasters(): bool
    {
        if ($this->hasPermissionSafe('manage-masters') || $this->hasPermissionSafe('setting-approval')) {
            return true;
        }

        return $this->roles->pluck('name')->contains(function ($role) {
            return stripos($role, 'admin') !== false;
        });
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
