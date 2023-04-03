<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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

    public function alumnosSinClase() {

        if ( isset($this->alumnos_sin_clase) && !empty($this->alumnos_sin_clase) ) {
            return $this->alumnos_sin_clase;
        }

        $nivel = $this->nivel;
        $edad = $this->edad;

        $sinClase = DB::table('users')
            ->leftJoin('class_student','class_student.user_id','=','users.id')
            ->select('users.id')
            ->where('users.user_type','=',2)
            ->whereNull('class_student.class_id');
        //$sql = vsprintf(str_replace(array('?'), array('\'%s\''), $sinClase->toSql()), $sinClase->getBindings()); dd($sql);

        $usersQuery = DB::table('usermetas')
            ->join('usermetas AS um2', function($join) use ($edad){
                $join->on('usermetas.user_id','=','um2.user_id')
                ->where('um2.metakey','=','edad')
                ->where('um2.metaval','=',$edad);
            })
            ->joinSub($sinClase, 'sinclases', function($join) {
                $join->on('usermetas.user_id','=','sinclases.id');
            })
            ->select('usermetas.user_id')
            ->where('usermetas.metakey','=','nivel')
            ->where('usermetas.metaval','=',$nivel);
        //$sql = vsprintf(str_replace(array('?'), array('\'%s\''), $usersQuery->toSql()), $usersQuery->getBindings()); dd($sql);

        $userResult = $usersQuery->get();

        $this->alumnos_sin_clase = \App\Models\User::whereIn('id', $userResult->pluck('user_id')->all())->get();

        return $this->alumnos_sin_clase;
    }

    public function alumnosSinClaseNums() {

        $arrResult = array('grupal'=>0, 'particular'=>0,'suscripciones'=>[]);

        if ( !isset($this->alumnos_sin_clase) || empty($this->alumnos_sin_clase) ) {
            $this->alumnosSinClase();
        }

        foreach( config('wiseabc.suscripcion_labels') AS $sid=>$label ) {
            //echo "sid: {$sid} \n";
            $arrResult['suscripciones'][$sid] = array();

            $_res = $this->alumnos_sin_clase->filter(function($item,$key) use($sid){
                return $sid==$item->suscripcion;
            });

            $arrResult['suscripciones'][$sid] = $_res->count();
        }

        foreach( $arrResult['suscripciones'] AS $sid=>$count ) {
            if ($sid<5) {
                $arrResult['grupal'] = $arrResult['grupal'] + $count;
            }
            else {
                $arrResult['particular'] = $arrResult['particular'] + $count;
            }
        }

        return $arrResult;
    }
}