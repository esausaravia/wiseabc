<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //protected $with = ['usermetas'];

    protected static function booted()
    {
        static::saved(function ($user) {

            /*Log::channel('stderr')->info('saved event ', [
                'horario' => $user->getMeta('horario')
            ]);*/
        });
    }

    /**
     * Relationships
     */
    public function usermetas(): Collection
    {
        return $this->hasMany(Usermeta::class);
    }

    public function horarios(): Collection
    {
        return $this->hasMany(UserHorario::class)->orderBy('dia');
    }

    public function teachclasses(): Collection
    {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }

    public function classrooms(): Collection
    {
        return $this->belongsToMany(Classroom::class, 'class_student', 'user_id', 'class_id')->withTimestamps()->orderByPivot('created_at', 'desc');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function asistencias()
    {
        return $this->hasMany(Attendance::class);
    }

    //old metakey suscripcion
    public function billingplans()
    {
        return $this->belongsToMany(BillingPlan::class, 'subscriptions', 'user_id', 'billing_plan_id')->as('subscription')->withTimestamps()->withPivot('id', 'status')->orderByPivot('created_at', 'desc');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class)->orderBy('created_at', 'desc');
    }

    public function paypal()
    {
        return $this->morphOne(Paypalobj::class, 'paypalable')->ofMany([
            'created_at' => 'max',
            'id' => 'max',
        ], function ($query) {
            $query->where('api', 'paypal');
        });

        return $this->morphOne(Paypalobj::class, 'paypalable');
    }

    public function stripe()
    {
        return $this->morphOne(Paypalobj::class, 'paypalable')->ofMany([
            'created_at' => 'max',
            'id' => 'max',
        ], function ($query) {
            $query->where('api', 'stripe');
        });
    }

    /**
     * Accessors
     */
    protected function edadLabel(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $config = config('wiseabc.edad_labels');
                if (! is_array($config)) {
                    $config = [];
                }

                return $this->edad !== null && ! empty($config[($this->edad)]) ? $config[($this->edad)] : $this->edad;
            },
        );
    }

    protected function nivelLabel(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $config = config('wiseabc.nivel_labels');
                if (! is_array($config)) {
                    $config = [];
                }

                return $this->nivel !== null && ! empty($config[($this->nivel)]) ? $config[($this->nivel)] : $this->nivel;
            },
        );
    }

    protected function ritmoLabel(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $config = config('wiseabc.ritmo_labels');
                if (! is_array($config)) {
                    $config = [];
                }

                return $this->ritmo !== null && ! empty($config[($this->ritmo)]) ? $config[($this->ritmo)] : $this->ritmo;
            },
        );
    }

    protected function currentClassroom(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                return $this->classrooms()->with(['curso', 'teacher', 'horarios'])->where('status', 'active')->where('ends_at', '>=', now())->first();
            }
        );
    }

    /**
     * Class Methods
     */
    public function getMeta(string $mkey = ''): string
    {
        if (empty($mkey)) {
            return null;
        }
        $return = '';

        foreach ($this->usermetas as $meta) {
            if ($meta->metakey === $mkey || $meta->id === $mkey) {
                $return .= $meta->metaval;
            }
        }

        return $return !== '' ? $return : null;
    }

    /**
     * Guarda el $input array como metakey => metaval en la tabla usermetas
     *
     * @return array metas actualizados
     */
    public function saveMetas(array $input = []): array
    {
        $model_cols = array_keys($this->getOriginal());
        array_push($model_cols, '_token', '_method', 'password_confirmation');

        $metasrc = [];
        foreach ($input as $_input_key => $_input_val) {
            if ($_input_val === false || $_input_val === null || in_array($_input_key, $model_cols) !== false) {
                continue;
            }
            if (is_array($_input_val) || is_object($_input_val)) {
                $_input_val = json_encode($_input_val);
            }
            if ($this->getMeta($_input_key) !== $_input_val) {
                $metasrc[$_input_key] = $_input_val;
            }
        }

        foreach ($metasrc as $_metakey => $_metaval) {
            if (strlen($_metaval) > 250) {
                $this->usermetas()->where('metakey', $_metakey)->delete();
                $_metaval_arr = str_split($_metaval, 250);

                foreach ($_metaval_arr as $__mv) {
                    $this->usermetas()->create(['metakey' => $_metakey, 'metaval' => $__mv]);
                }
            } else {
                $this->usermetas()->updateOrCreate(['metakey' => $_metakey], ['metaval' => $_metaval]);
            }
        }//endforeach
        $this->refresh();

        return $metasrc;
    }

    /**
     * Actualiza los horarios eliminando todos los anteriores
     *
     * @param  array  $horarios [1=>[9,10,11]]
     */
    public function saveHorarios(array $horarios = []): array
    {
        if (empty($horarios) || ! is_array($horarios)) {
            return false;
        }

        $deleted = \Illuminate\Support\Facades\DB::delete('DELETE FROM user_horarios WHERE user_id='.$this->id);

        $horarios = \App\Http\Controllers\WiseabcController::transformHorariosTimezone($horarios, '-0600', $this->getMeta('timezone'));

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
     * Devuelve los horarios del usuario como Array
     */
    public function getHorariosArray(int $dia = 0): array
    {
        $arrHorarios = [];
        foreach ($this->horarios as $horario) {
            if (empty($arrHorarios[($horario->dia)]) || ! is_array($arrHorarios[($horario->dia)])) {
                $arrHorarios[($horario->dia)] = [];
            }
            $arrHorarios[($horario->dia)][] = $horario->hr;
        }

        return empty($dia) ? $arrHorarios : (! empty($arrHorarios[($dia)]) ? $arrHorarios[($dia)] : []);
    }

    public function getHorarioArray($dia = 0): array
    {
        return $this->getHorariosArray($dia);
    }

    public function getHorariosArrayTimezoned($dia = 0)
    {
        return $this->transformHorariosTimezone($this->getHorariosArray($dia));
    }

    public function transformHorariosTimezone($horarios = null, $timezone = null, $fromTz = '-0600')
    {
        if (empty($horarios) || ! is_array($horarios)) {
            $horarios = $this->getHorariosArray();
        }
        if (! is_string($timezone)) {
            $timezone = $this->getMeta('timezone');
        }

        return \App\Http\Controllers\WiseabcController::transformHorariosTimezone($horarios, $timezone, $fromTz);
    }

    public function horariosOcupados($dia = 0)
    {
        $arrOcupado = [];

        foreach ($this->teachclasses as $clase) {

            $arr2 = $clase->getHorarioArray();
            foreach ($arr2 as $_dia => $arrHr) {
                if (empty($arrOcupado[$_dia]) || ! is_array($arrOcupado[$_dia])) {
                    $arrOcupado[$_dia] = $arrHr;
                } else {
                    $arrOcupado[$_dia] = array_merge($arrOcupado[$_dia], $arrHr);
                }
            }
        }

        return empty($dia) ? $arrOcupado : (! empty($arrOcupado[$dia]) ? $arrOcupado[$dia] : []);
    }

    public function horariosDisponibles($dia = false)
    {

        $arrHorarios = $this->getHorarioArray();
        $arrOcupado = $this->horariosOcupados();

        foreach ($arrOcupado as $_dia => $arrHrs) {
            foreach ($arrHrs as $hr) {
                if (! empty($arrHorarios[($_dia)]) && ($rmvkey = array_search($hr, $arrHorarios[($_dia)])) !== false) {
                    unset($arrHorarios[($_dia)][$rmvkey]);
                }
            }
        }
        foreach ($arrHorarios as $_dia => $arrHrs) {
            if (empty($arrHrs)) {
                unset($arrHorarios[$_dia]);
            }
        }

        return empty($dia) ? $arrHorarios : (! empty($arrHorarios[($dia)]) ? $arrHorarios[($dia)] : []);
    }

    /**
     * Save profile pic
     *
     * @return bool|string failure|filename
     */
    public function saveProfilePic(\Illuminate\Http\UploadedFile $file)
    {
        if (! is_a($file, 'Illuminate\Http\UploadedFile')) {
            dd('NO ES Illuminate\Http\UploadedFile');

            return false;
        }

        switch ($this->user_type) {
            default:
                $filePath = public_path('img/users');
                break;
            case 2:
                $filePath = public_path('img/students');
                break;
            case 3:
                $filePath = public_path('img/teachers');
                break;
        }
        /**
         * pendiente unlink anterior
         */
        $img = Image::make($file->getRealPath());

        $filename = $this->id.'_'.time().'-320x320.'.$file->guessExtension();
        $img->fit(320)->save($filePath.'/'.$filename);

        $filename = $this->id.'_'.time().'-160x160.'.$file->guessExtension();
        $img->fit(160)->save($filePath.'/'.$filename);

        $filename = $this->id.'_'.time().'-80x80.'.$file->guessExtension();
        $img->fit(80)->save($filePath.'/'.$filename);

        return $filename;
    }

    /**
     * Obtener public url de profile pic
     *
     * @return string asset url
     */
    public function getProfilePic(int $size = 80): string
    {
        $fileName = $this->getMeta('profilepic');
        if (empty($fileName)) {
            return null;
        }

        switch ($this->user_type) {
            default:
                $filePath = ('img/users');
                break;
            case 2:
                $filePath = ('img/students');
                break;
            case 3:
                $filePath = ('img/teachers');
                break;
        }

        if ($size === 80 || ! is_int($size)) {
            return asset($filePath.'/'.$fileName);
        }

        $fileName = preg_replace('/\-80x80\./', '-'.$size.'x'.$size.'.', $fileName);

        return asset($filePath.'/'.$fileName);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()->with(['billingPlan', 'paypal', 'stripe'])->where('status', 'ACTIVE')->first();
    }

    /**
     * Devuelve el atributo nativo del Modelo o el usermeta
     */
    public function __get($gkey)
    {
        $attr = $this->getAttribute($gkey);
        if ($attr !== null) {
            return $attr;
        }
        $meta = $this->getMeta($gkey);

        return $meta !== null ? $meta : $attr;
    }
}
