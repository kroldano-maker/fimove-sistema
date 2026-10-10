<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Concerns\Bitacorable;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'apellido', 'email', 'password', 'rol', 'estado'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use Bitacorable;

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
            'bloqueado_hasta' => 'datetime',
        ];
    }

    // Un usuario puede haber registrado varios pacientes
    public function pacientes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Paciente::class);
    }

    // Un usuario puede tener varias citas asignadas
    public function citas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Cita::class);
    }

    // Un usuario puede haber generado varios reportes
    public function reportes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Reporte::class);
    }

    public function etiquetaBitacora(): string
    {
        return "{$this->name} {$this->apellido} ({$this->email})";
    }
}