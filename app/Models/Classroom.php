<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Classroom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['curso_id','teacher_id', 'status', 'tipo', 'ritmo', 'start'];

    protected $casts = [
        'start'=>'date:Y-m-d',
        'ends_at'=>'date:Y-m-d',
    ];

    public static $arrTipos = [
        1 => 'Grupal',
        2 => 'Individual'
    ];

    protected static function booted() {
        static::saving(function($clase){
            $semanas = ceil($clase->curso->duracion / $clase->ritmo);
            $clase->ends_at = Carbon::parse($clase->start)->addWeek($semanas)->format('Y-m-d');
        });
    }

    /**
     * Relationships
     */

    /**
     * Devuelve el Curso correspondiente
     * @return App\Models\Curso
     */
    public function curso(){
        return $this->belongsTo(Curso::class);
    }

    /**
     * Devuelve profesor asignado a la clase
     * @return App\Models\User
     */
    public function teacher(){
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    /**
     * Devuelve estudiantes asignados a la clase
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function students() {
        return $this->belongsToMany(User::class, 'class_student', 'class_id', 'user_id')->withTimestamps();
    }

    /**
     * Devuelve los horarios de la clase
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function horarios() {
        return $this->hasMany(ClassHorario::class, 'class_id')->orderBy('dia');
    }

    /**
     * Devuelve las siguientes agendas
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function schedules() {
        return $this->hasMany(Schedule::class, 'class_id')->orderBy('fechahora');
    }

    public function attendances() {
        return $this->hasMany(Attendance::class, 'class_id')->orderBy('fechahora');
    }

    /**
     * Accessors
     */
    protected function tipoLabel(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => !empty( self::$arrTipos[( $attributes['tipo'] )] ) ? self::$arrTipos[( $attributes['tipo'] )] : $attributes['tipo'],
        );
    }
    protected function ritmoLabel(): Attribute
    {
        return Attribute::make(
            get: function($value, $attributes) {
                $config = config('wiseabc.ritmo_labels');
                return !empty( $config[( $attributes['ritmo'] )] ) ? $config[( $attributes['ritmo'] )] : $attributes['ritmo'];
            },
        );
    }
    /*
    protected function start(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => Carbon::create($value),
        );
    }*/

    /**
     * Class Methods
     */

    /**
     * Devuelve cuantas semanas faltan para que termine
     * @return int
     */
    public function endsInWeeks() {
        $start = Carbon::now();
        return Carbon::parse($this->ends_at)->diffInWeeks( $start );
    }

    public function getSuscripcion() {
        $arrSuscripciones = config('wiseabc.suscripciones');
        foreach($arrSuscripciones AS $sid=>$suscripcion) {
            if ((int)$suscripcion['ritmo']===$this->ritmo && (int)$suscripcion['tipo']===$this->tipo ) {
                return $sid;
            }
        }
        return false;
    }

    /**
     * Devuelve los horarios de la clase como Array
     * @return array
     */
    public function getHorarioArray(){
        $arrHorarios = array();
        foreach($this->horarios AS $horario) {
            $arrHorarios[( $horario->dia )][] = $horario->hr;
        }
        return $arrHorarios;
    }
    public function getHorariosArray(){
        return $this->getHorarioArray();
    }

    /**
     * Actualiza los horarios eliminando todos los anteriores
     * @param array horarios [1=>[13,14], 3=>[13,14], 5=>[13,14] ]
     * @return array
     */
    public function saveHorarios($horarios) {

        if (empty($horarios) || !is_array($horarios) ){
            return false;
        }

        $deleted = \Illuminate\Support\Facades\DB::delete('DELETE FROM class_horarios WHERE class_id='.$this->id);

        foreach( $horarios AS $dia=>$arrHrs ) {
            if ( !is_array($arrHrs) ) {
                continue;
            }

            foreach( $arrHrs AS $hr ) {
                $this->horarios()->create([
                    'dia'=>$dia,
                    'hr' => $hr
                ]);
            }
        }
        $this->refresh();
        return $this->horarios;
    }

    public function sigFechaHora($offset='') {
        $enWeekdays = config('wiseabc.en_weekdays');

        if ( is_object($offset) && class_basename($offset)==='Carbon' ) {
            $hoy = $offset;
        }
        else if ( is_string($offset) ) {
            $hoy = new Carbon($offset, 'America/Mexico_City');
            $hoy->locale('es');
        }
        else {
            $hoy = now('America/Mexico_City')->locale('es');
        }

        $hoy_diasem = (int)$hoy->isoFormat('d');
        $hr_actual = (int)$hoy->isoFormat('H');
        $min_actual = (int)$hoy->isoFormat('m');

        foreach($this->horarios AS $horario) {

            if ( $horario->dia===$hoy_diasem ) {

                if ( $horario->hr>$hr_actual ) {
                    $horario->next = $hoy->copy()->hour($horario->hr)->minute(0);
                    continue;
                }
                else if ( $horario->hr===$hr_actual && $min_actual<40 ) {
                    $horario->next = $hoy->copy()->hour($horario->hr)->minute(0);
                    continue;
                }
            }


            $horario->next = $hoy->copy()->next( $enWeekdays[($horario->dia)] )->hour($horario->hr)->minute(0);
            //echo print_r($horario->next, true).PHP_EOL;
        }
        return $this->horarios->sortBy('next')->first()->next;
    }
}
