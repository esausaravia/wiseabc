<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curso extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['status','edad','nivel','duracion','name'];

    //protected $cast = ['start_at'=>'date:Y-m-d'];

    public function clases() {
        return $this->hasMany(Classroom::class);
    }

    /**
     * Accessors
     */
    protected function edadLabel(): Attribute
    {
        return Attribute::make(
            get: function($value, $attributes) {
                $config = config('wiseabc.edad_labels');
                if (!is_array($config) ) {
                    $config = array();
                }
                return !empty( $config[( $attributes['edad'] )] ) ? $config[( $attributes['edad'] )] : $attributes['edad'];
            },
        );
    }
    protected function nivelLabel(): Attribute
    {
        return Attribute::make(
            get: function($value, $attributes) {
                $config = config('wiseabc.nivel_labels');
                if (!is_array($config) ) {
                    $config = array();
                }
                return !empty( $config[( $attributes['nivel'] )] ) ? $config[( $attributes['nivel'] )] : $attributes['nivel'];
            },
        );
    }
}