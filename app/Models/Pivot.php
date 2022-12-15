<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pivot extends Model
{
    use HasFactory;


    public function concept() {
        return $this->belongsTo(PaymentConcept::class);
    }


    public function receipt() {
        return $this->belongsTo(Receipt::class);
    }
}
