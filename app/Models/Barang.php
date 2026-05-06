<?php

/** @author Joni Yoga Kusuma - 254107023003 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    protected $fillable = [
        'user_id',
        'nama_barang',
        'deskripsi',
        'harga',
        'kategori_id',
        'status',
        'area_id',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function fotoBarangs(): HasMany
    {
        return $this->hasMany(FotoBarang::class);
    }

    public function fotoPrimary()
    {
        return $this->hasOne(FotoBarang::class)->where('is_primary', true);
    }

    public function getHargaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    public function scopeByArea($query, $areaId)
    {
        return $query->where('area_id', $areaId);
    }

    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }
}
