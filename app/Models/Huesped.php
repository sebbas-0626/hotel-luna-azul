<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Modelo: huéspedes del hotel 
class Huesped extends Model
{
    protected $table = 'huespedes';
    protected $fillable = [
        'nombre_completo','tipo_documento','numero_documento','direccion',
        'telefono','celular','edad','email','contacto_emergencia','parentesco_contacto'
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'huesped_id');
    }
}
