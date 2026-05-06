<?php

/** @author Joni Yoga Kusuma - 254107023003 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class FotoBarang extends Model
{
    protected $fillable = [
        'barang_id',
        'file_path',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }
}
