<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function hasPermission(string $permission): bool
    {
        return $this->role && $this->role->hasPermission($permission);
    }

    public function isActive(): bool
    {
        return $this->is_active && ($this->role ? $this->role->is_active : false);
    }

    public function getRoleName(): string
    {
        return $this->role ? $this->role->display_name : 'No Role';
    }

    public function jamaahProfile()
    {
        return $this->hasOne(JamaahProfile::class);
    }

    public function hasCompletedRegistration(): bool
    {
        if (!$this->hasRole('jamaah')) {
            return true; // Non-jamaah users don't need registration
        }

        return $this->jamaahProfile && $this->jamaahProfile->isRegistrationComplete();
    }
}
