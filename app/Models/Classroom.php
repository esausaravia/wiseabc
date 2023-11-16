<?php

namespace App\Models;

use App\Models\Curso;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Schedule;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Classroom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['curso_id', 'teacher_id', 'status', 'tipo', 'ritmo', 'start'];

    protected $casts = [
        'start' => 'date:Y-m-d',
        'ends_at' => 'date:Y-m-d',
    ];

    public static $arrTipos = [
        1 => 'Grupal',
        2 => 'Particular',
    ];

    protected static function booted()
    {
        static::saving(function ($clase) {

            $semanas = ceil($clase->curso->duracion / $clase->ritmo);

            if (is_object($clase->start) && class_basename($clase->start) === 'Carbon') {
                $clase->start->setTimezone('UTC');
                $clase->ends_at = $clase->start->copy()->addWeek($semanas);
            } elseif (is_string($clase->start)) {
                $fecha = \Carbon\Carbon::parse($clase->start);
                $clase->ends_at = $fecha->addWeek($semanas);
            }
        });
    }

    /**
     * Relationships
     */

    /**
     * Devuelve el Curso correspondiente
     *
     * @return App\Models\Curso
     */
    public function curso(): Curso
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * Devuelve profesor asignado a la clase
     *
     * @return App\Models\User
     */
    public function teacher(): User
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    /**
     * Devuelve estudiantes asignados a la clase
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function students(): Collection
    {
        return $this->belongsToMany(User::class, 'class_student', 'class_id', 'user_id')->withTimestamps();
    }

    /**
     * Devuelve los horarios de la clase
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function horarios(): Collection
    {
        return $this->hasMany(ClassHorario::class, 'class_id')->orderBy('dia');
    }

    /**
     * Devuelve las siguientes agendas
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function schedules(): Collection
    {
        return $this->hasMany(Schedule::class, 'class_id')->orderBy('fechahora');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'class_id')->orderBy('fechahora', 'desc');
    }

    /**
     * Accessors
     */
    protected function tipoLabel(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $config = config('wiseabc.clase_tipo_labels');

                return ! empty($config[($attributes['tipo'])]) ? $config[($attributes['tipo'])] : $attributes['tipo'];
            },
        );
    }

    protected function ritmoLabel(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $config = config('wiseabc.ritmo_labels');

                return ! empty($config[($attributes['ritmo'])]) ? $config[($attributes['ritmo'])] : $attributes['ritmo'];
            },
        );
    }

    protected function edadLabel(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (! is_object($this->curso) || empty($this->curso->edad)) {
                    return null;
                }

                $config = config('wiseabc.edad_labels');

                return ! empty($config[($this->curso->edad)]) ? $config[($this->curso->edad)] : $this->curso->edad;
            },
        );
    }

    protected function nivelLabel(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (! is_object($this->curso) || empty($this->curso->nivel)) {
                    return null;
                }

                $config = config('wiseabc.nivel_labels');

                return ! empty($config[($this->curso->nivel)]) ? $config[($this->curso->nivel)] : $this->curso->nivel;
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
     *
     * @return int
     */
    public function endsInWeeks(): int
    {
        $start = Carbon::now();

        return Carbon::parse($this->ends_at)->diffInWeeks($start);
    }

    /**
     * Devuelve los horarios de la clase como Array
     *
     * @return array
     */
    public function getHorariosArray(): array
    {
        $arrHorarios = [];
        foreach ($this->horarios as $horario) {
            $arrHorarios[($horario->dia)][] = $horario->hr;
        }

        return $arrHorarios;
    }

    public function getHorarioArray()
    {
        return $this->getHorariosArray();
    }

    /**
     * Actualiza los horarios eliminando todos los anteriores
     *
     * @param array horarios [1=>[13,14], 3=>[13,14], 5=>[13,14] ]
     * @return array
     */
    public function saveHorarios($horarios): array
    {

        if (empty($horarios) || ! is_array($horarios)) {
            return false;
        }

        $deleted = \Illuminate\Support\Facades\DB::delete('DELETE FROM class_horarios WHERE class_id='.$this->id);

        foreach ($horarios as $dia => $arrHrs) {
            if (! is_array($arrHrs)) {
                continue;
            }

            foreach ($arrHrs as $hr) {
                $this->horarios()->create([
                    'dia' => $dia,
                    'hr' => $hr,
                ]);
            }
        }
        $this->refresh();

        return $this->horarios;
    }

    /**
     * Calcula la fecha y hora de la siguiente clase con base en los horarios
     *
     * @param  Carbon::class|string  $offset
     * @return Carbon::class
     */
    public function sigFechaHora($offset = ''): \Carbon::class
    {
        $enWeekdays = config('wiseabc.en_weekdays');

        if (is_object($offset) && class_basename($offset) === 'Carbon') {
            $hoy = $offset->setTimezone('-0600');
        } elseif (is_string($offset)) {
            $hoy = new Carbon($offset, '-0600');
        } elseif (now()->lessThan($this->start)) {
            $hoy = $this->start->copy()->setTimezone('-0600');
        } else {
            $hoy = now('-0600');
        }
        $hace40mins = $hoy->copy()->subMinutes(40);

        $this->horarios->transform(function ($horario, $hkey) use ($enWeekdays, $hoy, $hace40mins) {
            $horario->next = $hoy->copy()->subDay()->next($enWeekdays[($horario->dia)].' '.$horario->hr.':00');

            if ($horario->next->lessThan($hace40mins)) {
                $horario->next->next($enWeekdays[($horario->dia)].' '.$horario->hr.':00');
            }

            return $horario;
        });

        return $this->horarios->sortBy('next')->first()->next;
    }//sigFechaHora

    /**
     * @param  Carbon::class|string  $offset
     * @return App\Models\Schedule
     */
    public function nextSchedule($offset = null): Schedule
    {
        if (! is_object($offset)) {
            if (is_string($offset)) {
                $offset = Carbon::parse($offset);
            } else {
                $offset = now();
            }
        }

        return $this->schedules()->where('fechahora', '>', $offset->subMinutes(6))->first();
    }
}
