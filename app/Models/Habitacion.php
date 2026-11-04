<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Modelo: habitaciones del hotel
class Habitacion extends Model
{
    protected $table = 'habitaciones';
    protected $fillable = ['numero','tipo','capacidad','precio','estado'];

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'habitacion_id');
    }
}
