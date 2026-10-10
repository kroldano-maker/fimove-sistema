<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $fillable = ['user_id', 'email', 'accion', 'descripcion', 'ip', 'fecha'];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function registrar(string $accion, ?string $descripcion = null, ?int $userId = null, ?string $email = null): void
    {
        static::create([
            'user_id' => $userId ?? auth()->id(),
            'email' => $email ?? auth()->user()?->email,
            'accion' => $accion,
            'descripcion' => $descripcion,
            'ip' => request()->ip(),
            'fecha' => now(),
        ]);
    }
}