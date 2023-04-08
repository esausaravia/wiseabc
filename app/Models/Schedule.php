<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['class_id','teams_id','fechahora'];

    protected $casts = [
        'fechahora'=>'datetime'
    ];

    public $timestamps = false;

    /**
     * Relationships
     */
    public function classroom(){
        return $this->belongsTo(Classroom::class);
    }

    public function teamsInfo(){
        return $this->belongsTo(Teamsinfo::class, 'teams_id', 'id');
    }
}
