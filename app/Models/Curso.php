<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

        $alumnosSinClase = \App\Models\User::whereHas('usermetas', function($query){
            $query->where('metakey','ritmo')->orWhere('metakey','clase_tipo');
        })->whereIn('id', $userResult->pluck('user_id')->all() );
        //$sql = vsprintf(str_replace(array('?'), array('\'%s\''), $alumnosSinClase->toSql()), $alumnosSinClase->getBindings()); dd($sql);
        $this->alumnos_sin_clase = $alumnosSinClase->get();

        return $this->alumnos_sin_clase;
    }

    public function alumnosSinClaseNums($refresh=false) {

        if ( !$refresh && isset($this->alumnos_sin_clase_nums) && !empty($this->alumnos_sin_clase_nums) ) {
            return $this->alumnos_sin_clase_nums;
        }

        $arrResult = array('grupo_subtotal'=>0, 'particular_subtotal'=>0, 't1'=>array(), 't2'=>[]);

        if ( !isset($this->alumnos_sin_clase) || empty($this->alumnos_sin_clase) ) {
            $this->alumnosSinClase();
        }

        foreach( BillingPlan::all() AS $billPlan ) {
            //echo "sid: {$sid} \n";

            $bpTipo = $billPlan->tipo;
            $bpRitmo = $billPlan->ritmo;

            $_res = $this->alumnos_sin_clase->filter(function($item, $key) use ($bpTipo, $bpRitmo){
                return $bpTipo==$item->clase_tipo && $bpRitmo==$item->ritmo;
            });

            //if grupal
            if ( $billPlan->tipo==1 )
            {
                $arrResult['t1'][( $billPlan->ritmo )] = $_res->count();
                $arrResult['grupo_subtotal'] = $arrResult['grupo_subtotal'] + $_res->count();
            }
            else //individual
            {
                $arrResult['t2'][( $billPlan->ritmo )] = $_res->count();
                $arrResult['particular_subtotal'] = $arrResult['particular_subtotal'] + $_res->count();
            }
        }
        $this->alumnos_sin_clase_nums = $arrResult;
        return $this->alumnos_sin_clase_nums;
    }
}