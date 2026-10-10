<?php

namespace App\Models\Concerns;

use App\Models\Bitacora;

trait Bitacorable
{
    public static function bootBitacorable(): void
    {
        static::created(function ($model) {
            Bitacora::registrar(
                'Creación',
                self::etiquetaModelo() . ' creado — ' . $model->etiquetaBitacora()
            );
        });

        static::updated(function ($model) {
            $cambios = $model->getChanges();
                unset(
                    $cambios['updated_at'],
                    $cambios['remember_token'],
                    $cambios['password'],
                    $cambios['intentos_fallidos'],
                    $cambios['bloqueado_hasta']
                );

            if (empty($cambios)) {
                return;
            }

            $detalle = collect($cambios)->map(function ($valor, $campo) use ($model) {
                $anterior = $model->getOriginal($campo);
                return "{$campo}: '{$anterior}' → '{$valor}'";
            })->implode(', ');

            Bitacora::registrar(
                'Actualización',
                self::etiquetaModelo() . ' actualizado (' . $model->etiquetaBitacora() . '): ' . $detalle
            );
        });

        static::deleted(function ($model) {
            Bitacora::registrar(
                'Eliminación',
                self::etiquetaModelo() . ' eliminado — ' . $model->etiquetaBitacora()
            );
        });
    }

    protected static function etiquetaModelo(): string
    {
        return class_basename(static::class);
    }

    public function etiquetaBitacora(): string
    {
        return (string) $this->getKey();
    }
}