<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = ['attendance_id','status','amount'];

    public $timestamps = false;

    //hasmany payments
    public function payment() {
        return $this->belongsTo(Payment::class); //(Payment::class, 'payment_id', 'id');
    }

    public function conceptos() {
        //                         (Model::class,          'table',            'current_model_id', 'related_model_id');
        return $this->belongsToMany(PaymentConcept::class, 'receipt_pconcept', 'receipt_id', 'pconcept_id')->withPivot('amount');
    }


}
