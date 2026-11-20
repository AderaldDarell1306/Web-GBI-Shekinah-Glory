<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        'role',
        'wilayah_id',
        'telepon',
        'status',
        'failed_login_attempts',
        'locked_until',
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
            'locked_until' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function isDeveloper(): bool
    {
        return $this->role === 'developer';
    }

    public function isSuperAdmin(): bool
    {
        return in_array($this->role, ['developer', 'super_admin']);
    }

    public function isGembala(): bool
    {
        return $this->role === 'gembala';
    }

    public function isBendahara(): bool
    {
        return $this->role === 'bendahara';
    }

    public function isKetuaCool(): bool
    {
        return $this->role === 'ketua_cool';
    }

    public function canManageContent(): bool
    {
        return in_array($this->role, ['developer', 'super_admin']);
    }

    public function canManageKeuangan(): bool
    {
        return in_array($this->role, ['developer', 'super_admin', 'bendahara']);
    }

    public function canViewKeuangan(): bool
    {
        return in_array($this->role, ['developer', 'super_admin', 'gembala', 'bendahara']);
    }

    public function isJemaat(): bool
    {
        return $this->role === 'jemaat';
    }

    public function canCrudJemaat(): bool
    {
        return in_array($this->role, ['developer', 'super_admin', 'admin']);
    }

    public function canManagePastoral(): bool
    {
        return in_array($this->role, ['developer', 'super_admin', 'gembala']);
    }

    public function canManageJemaatAll(): bool
    {
        return in_array($this->role, ['developer', 'super_admin', 'gembala', 'admin']);
    }
}
