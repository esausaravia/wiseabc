<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'status', 'amount'];

    /**
     * Relationships
     */
    /**
     * Devuelve el profesor que recibe el pago
     *
     * @return User::class
     */
    public function user(): \User::class
    {
        return $this->belongsTo(User::class);
    }

    public function paypal()
    {
        return $this->morphOne(Paypalobj::class, 'paypalable');
    }
}
