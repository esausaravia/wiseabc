<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paypalobj extends Model
{
    use HasFactory;

    protected $fillable = ['api','api_id','api_object'];

    protected function apiObject(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ($json = json_decode($value) )!==null ? $json : $value,
            set: fn($value) => json_encode($value),
        );
    }

    /**
     * Relationships
     */
    /**
     * Polymorphic
     */
    public function paypalable()
    {
        return $this->morphTo();
    }

}
