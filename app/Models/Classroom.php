<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['curso_id','teacher_id', 'status', 'tipo', 'ritmo', 'start'];

    public static $arrTipo = [
        1 => 'Grupal',
        2 => 'Individual'
    ];

    protected static function booted() {
        static::saving(function($clase){
            $semanas = ceil($clase->curso->duracion / $clase->ritmo);
            $clase->ends_at = \Illuminate\Support\Carbon::parse($clase->start)->addWeek($semanas)->format('Y-m-d');
        });
    }

    /**
     * Relationships
     */

    /**
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function horarios() {
        return $this->hasMany(ClassHorario::class, 'class_id')->orderBy('dia');
    }

    /**
     * @return App\Models\Curso
     */
    public function curso(){
        return $this->belongsTo(Curso::class);
    }

    /**
     * @return App\Models\User
     */
    public function teacher(){
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function students() {
        return $this->belongsToMany(User::class, 'class_student', 'class_id', 'user_id')->withTimestamps();
    }

    /**
     * Accessors
     */
    protected function tipoLabel(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => !empty( self::$arrTipo[( $attributes['tipo'] )] ) ? self::$arrTipo[( $attributes['tipo'] )] : $attributes['tipo'],
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
            get: fn($value, $attributes) => \Illuminate\Support\Carbon::create($value),
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
        $start = \Illuminate\Support\Carbon::now();
        return \Illuminate\Support\Carbon::parse($this->ends_at)->diffInWeeks( $start );
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

    public function nextSchedule() {
        $enWeekdays = config('wiseabc.en_weekdays');

        $hoy = now('America/Mexico_City')->locale('es');
        $hoy_diasem = $hoy->isoFormat('d');
        $hoy_hr = $hoy->isoFormat('H');
        $hoy_min = $hoy->isoFormat('H');

        foreach($this->horarios AS $horario) {

            if ( $horario->dia===(int)$hoy_diasem ) {

                if ( $horario->hr>(int)$hoy_hr ) {
                    $horario->next = $hoy->copy()->hour($horario->hr)->minute(0);
                    continue;
                }
                else if ( $horario->hr===(int)$hoy_hr && (int)$hoy_min<41 ) {
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
