<?php

/** @author Izza Dhafira Fanani - 244107020106 */

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_wa',
        'area_id',
        'role',
        'is_blocked',
        'blocked_at',
        'blocked_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_blocked'        => 'boolean',
            'blocked_at'        => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class);
    }
}
