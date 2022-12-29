<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['class_id','teams_id','fechahora'];

    protected $table = 'class_schedules';

    public $timestamps = false;

    public function class(){
        return $this->belongsTo(Classroom::class);
    }

    public function teamsInfo(){
        return $this->belongsTo(Teamsinfo::class);
    }
}
