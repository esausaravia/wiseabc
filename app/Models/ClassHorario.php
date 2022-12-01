<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassHorario extends Model
{
    use HasFactory;
    protected $primaryKey = null;

    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['class_id','dia','hr'];

    public function class(){
        return $this->belongsTo(Classroom::class);
    }

    protected function diaLabel(): Attribute
    {
        $arr = config('wiseabc.weekdays');
        return Attribute::make(
            get: fn ($value, $attributes) => !empty($arr[( $attributes['dia'] )]) ? $arr[( $attributes['dia'] )] : $attributes['dia'],
        );
    }
}
