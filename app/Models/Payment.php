<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;


    public function users() {
        return $this->belongsToMany(User::class);
    }

    public function recibos() {
        return $this->hasMany(Receipt::class); //(Receipt::class, 'payment_id', 'id');
    }

}
