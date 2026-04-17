<?php

/** @author Joni Yoga Kusuma - 254107023003 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $fillable = [
        'nama_kategori',
        'slug',
    ];

    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class);
    }
}
