<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHorario extends Model
{
    use HasFactory;
    protected $primaryKey = null;

    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['curso_id','dia','hr'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    protected function diaLabel(): Attribute
    {
        $arr = config('wiseabc.weekdays');
        return Attribute::make(
            get: fn ($value, $attributes) => !empty($arr[( $attributes['dia'] )]) ? $arr[( $attributes['dia'] )] : $attributes['dia'],
        );
    }
}
