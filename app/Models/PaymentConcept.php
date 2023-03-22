<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentConcept extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['concept','amount'];

    public function recibos() {
        //                         (Model::class, 'table', 'current_model_id', 'related_model_id');
        return $this->belongsToMany(Receipt::class, 'receipt_pconcept', 'pconcept_id', 'receipt_id')->withPivot('amount');
    }
}
