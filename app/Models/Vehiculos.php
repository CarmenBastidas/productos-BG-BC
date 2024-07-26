<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Vehiculos extends Model
{
    protected $table = 'vehiculos';
    protected $fillable = [
        'nombre',
        'marca',
        'modelo',
        'precio',
        'estado',
    ];

    protected $appends = ['deseo'];

    public function getDeseoAttribute(): bool
    {
        $detalle = VehiculosDeseo::where('vehiculo_id', $this->id)->where('user', 1)->where('estado', 1)->first();
        $result = false;
        if (!empty($detalle)) {
            $result = true;
        }
        return $result;
    }

}
