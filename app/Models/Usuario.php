<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Modelo: usuarios del sistema
class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $fillable = ['nombre', 'email', 'password', 'rol'];
    protected $hidden = ['password'];

    public function setPasswordAttribute($value)
    {
        if ($value !== null && $value !== '' && strpos($value, '$2y$') !== 0) {
            $this->attributes['password'] = password_hash($value, PASSWORD_BCRYPT);
        } else {
            $this->attributes['password'] = $value;
        }
    }
}
