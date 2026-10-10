<?php

return [
    'required' => 'El campo :attribute es obligatorio.',
    'email' => 'El campo :attribute debe ser un correo electrónico válido.',
    'unique' => 'Ya existe un registro con ese :attribute.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'min' => [
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
        'numeric' => 'El campo :attribute debe ser al menos :min.',
    ],
    'max' => [
        'string' => 'El campo :attribute no debe tener más de :max caracteres.',
        'numeric' => 'El campo :attribute no debe ser mayor que :max.',
    ],
    'date' => 'El campo :attribute debe ser una fecha válida.',
    'in' => 'El valor seleccionado para :attribute no es válido.',
    'exists' => 'El :attribute seleccionado no es válido.',
    'string' => 'El campo :attribute debe ser texto.',
    'numeric' => 'El campo :attribute debe ser un número.',

    'attributes' => [
        'paciente_id' => 'paciente',
        'fecha' => 'fecha',
        'hora' => 'hora',
        'motivo' => 'motivo',
        'estado' => 'estado',
        'nombres' => 'nombres',
        'apellidos' => 'apellidos',
        'dpi' => 'DPI',
        'fecha_nacimiento' => 'fecha de nacimiento',
        'telefono' => 'teléfono',
        'direccion' => 'dirección',
        'correo' => 'correo',
        'email' => 'correo',
        'password' => 'contraseña',
        'name' => 'nombre',
        'rol' => 'rol',
        'sesion' => 'sesión',
        'fecha_registro' => 'fecha de la sesión',
        'descripcion' => 'observaciones',
        'evolucion' => 'avance del paciente',
    ],
];