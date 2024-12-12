<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Importa a classe Str para manipulação de strings

// app/Models/Person.php
class Person extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'birthdate', 'unique_code', 'monthly_limit'];

    protected static function booted()
    {
        static::creating(function ($person) {
            // Gera um código único de 6 dígitos numéricos
            $numericCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Gera um código único com prefixo usando uniqid
            $uniqueIdCode = strtoupper(substr(uniqid('JC'), 0, 6));
            
            // Combina ambos os códigos
            $person->unique_code =  $uniqueIdCode . $numericCode;
        });
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
