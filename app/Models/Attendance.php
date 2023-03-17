<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id','class_id','teams_id','fechahora','duracion','puntual'];

    public function clase(){
        return $this->belongsTo(Classroom::class);
    }

    public function class(){
        return $this->belongsTo(Classroom::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function receipt(){
        return $this->hasOne(Receipt::class);
    }
}
