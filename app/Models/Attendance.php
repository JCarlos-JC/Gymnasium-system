<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['person_id', 'attended_at', 'session'];

    // public function person()
    // {
    //     return $this->belongsTo(Person::class);
    // }
//         public function person()
//     {
//         return $this->belongsTo(Person::class, 'unique_code', 'unique_code');
//     }

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

}
