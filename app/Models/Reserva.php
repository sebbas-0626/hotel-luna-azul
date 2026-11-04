<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Modelo: reservas
class Reserva extends Model
{
    protected $table = 'reservas';
    protected $fillable = [
        'huesped_id','habitacion_id','tipo_habitacion','fecha_ingreso','fecha_salida',
        'num_huespedes','estado'
    ];
    protected $casts = [
        'fecha_ingreso' => 'date',
        'fecha_salida' => 'date',
    ];

    public function huesped()
    {
        return $this->belongsTo(Huesped::class, 'huesped_id');
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class, 'habitacion_id');
    }
}
