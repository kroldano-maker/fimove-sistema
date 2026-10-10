<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reporte extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tipo',
    ];

    protected $casts = [
        'fecha_generacion' => 'datetime',
    ];

    // El reporte pertenece al usuario que lo generó
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}