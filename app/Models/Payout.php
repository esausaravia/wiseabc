<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payout extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id','status','reference','amount'];

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value/100,
            set: fn ($value) => floor($value*100),
        );
    }

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

    public function paypal(){
        return $this->morphOne(Paypalobj::class, 'paypalable');
    }
}
