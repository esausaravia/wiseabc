<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

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
        return $this->belongsTo(Classroom::class,'class_id');
    }

    public function teamsInfo(){
        return $this->belongsTo(Teamsinfo::class, 'teams_id');
    }

    /**
     * Accesors
     */
    public function endsAt(): Attribute
    {
        return Attribute::make(
            get:function($value,$attributes){
                return is_object($value) && class_basename($value)=='Carbon' ? $value : $this->fechahora->copy()->addMinutes(40);
            }
        );
    }
}
