<?php

/** @author Izza Dhafira Fanani - 244107020106 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    protected $fillable = [
        'nama_kecamatan',
        'kota',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class);
    }
}
