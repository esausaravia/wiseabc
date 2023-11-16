<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'class_id', 'teams_id', 'fechahora', 'duracion', 'puntual'];

    protected $casts = [
        'fechahora' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id', 'id');
    }

    public function clase()
    {
        return $this->belongsTo(Classroom::class, 'class_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payout()
    {
        return $this->belongsTo(Payout::class);
    }

    public function pago()
    {
        return $this->belongsTo(Payout::class);
    }

    public function pconcepts()
    {
        //     $this->belongsToMany(Model::class, 'table', 'current_model_id', 'related_model_id');
        return $this->belongsToMany(PaymentConcept::class, 'attendance_pconcept', 'attendance_id', 'pconcept_id')->as('recibo')->withPivot('amount');
    }
}
