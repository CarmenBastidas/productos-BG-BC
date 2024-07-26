<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class VehiculosDeseo extends Model
{

    protected $table = 'vehiculos_deseo';
    protected $fillable = [
        'vehiculo_id',
        'user_id',
        'estado',
    ];

}
