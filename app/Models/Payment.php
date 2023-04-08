<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id','reference','amount'];

    /**
     * Relationships
     */
    /**
     * Devuelve el profesor que recibe el pago
     * @return User::class
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function attendances() {
        return $this->hasMany(Attendance::class);
    }
    public function asistencias(){
        return $this->hasMany(Attendance::class);
    }

}
