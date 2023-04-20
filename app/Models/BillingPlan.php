<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillingPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name','tipo','ritmo','price'];

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value/100,
            set: fn ($value) => floor($value*100),
        );
    }

    /**
     * Relationships
     */
    public function paypal(){
        return $this->morphOne(Paypalobj::class, 'paypalable');
    }
}
