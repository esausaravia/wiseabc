<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
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
        'status'
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

    protected $with = ['usermetas'];

    protected static function booted() {
        static::saved(function($user){

            /*Log::channel('stderr')->info('saved event ', [
                'horario' => $user->getMeta('horario')
            ]);*/
        });
    }

    /**
     * Relationships
     */

    /**
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function usermetas() {
		return $this->hasMany(\App\Models\Usermeta::class);
	}

    /**
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function horarios() {
        return $this->hasMany(UserHorario::class)->orderBy('dia');
    }

    /**
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function teachclasses() {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }

    /**
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function classrooms() {
        return $this->belongsToMany(Classroom::class, 'class_student', 'user_id', 'class_id')->withTimestamps()->orderByPivot('created_at', 'desc');
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
                return $this->edad!==NULL && !empty($config[( $this->edad )]) ? $config[( $this->edad )] : $this->edad;
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
                return $this->nivel!==NULL && !empty($config[( $this->nivel )]) ? $config[( $this->nivel )] : $this->nivel;
            },
        );
    }

    /**
     * Class Methods
     */
	public function __get($gkey)
	{
        $attr = $this->getAttribute($gkey);
		if ( $attr!==NULL ) {
			return $attr;
		}
        $meta = $this->getMeta($gkey);
        return $meta!==NULL ? $meta : $attr;
	}

	/**
	 * @param string $mkey
	 * @return string
	 */
	public function getMeta($mkey="")
	{
		if ( empty($mkey) || empty($this->usermetas) || ! is_object($this->usermetas)
			|| ! is_a($this->usermetas, "Illuminate\Database\Eloquent\Collection") )
		{
			return NULL;
		}

        $return = '';

		foreach( $this->usermetas AS $meta )
		{
			if ($meta->metakey===$mkey || $meta->id===$mkey)
			{
				$return .= $meta->metaval;
			}
		}
		return $return!=='' ? $return : NULL;
	}

    /**
     * Guarda el $input array como metakey => metaval en la tabla usermetas
     * @param array $input
     * @return array metas actualizados
     */
    public function saveMetas( $input=array() ) {

        $model_keys = array_keys($this->getAttributes());
        array_push($model_keys, '_token', '_method', 'password_confirmation', 'horario', 'horarios');

        $metasrc = array();
        foreach($input AS $ik=>$ival)
        {
            if ($ival===false || $ival===null || in_array($ik, $model_keys) ) {
                continue;
            }
            if ( is_array($ival) || is_object($ival) ) {
                $ival = json_encode($ival, JSON_UNESCAPED_UNICODE);
            }
            if ( $this->$ik!==$ival ) {
                $metasrc[$ik] = $ival;
            }
        }

        foreach ( $metasrc AS $mk=>$mv)
        {
            if ( strlen($mv)>250 ) {
                $this->usermetas()->where('metakey', $mk)->delete();
                $mv_arr = str_split($mv, 250);

                foreach($mv_arr AS $__mv) {
                    $this->usermetas()->create(['metakey'=>$mk,'metaval'=>$__mv]);
                }
            }
            else if ( $this->$mk!==NULL ) {
                $this->usermetas()->where('metakey', $mk)->update(['metaval'=>$mv]);
            }
            else {
                $this->usermetas()->create(['metakey'=>$mk, 'metaval'=>$mv]);
            }
        }//endforeach
        $this->refresh();
        return $metasrc;
    }

    /**
     * Devuelve los horarios del usuario como Array
     * @param int $dia
     * @return array
     */
    public function getHorarioArray($dia=0){
        $arrHorarios = array();
        foreach($this->horarios AS $horario) {
            if (empty($arrHorarios[( $horario->dia )]) || !is_array($arrHorarios[( $horario->dia )])) {
                $arrHorarios[( $horario->dia )] = array();
            }
            $arrHorarios[( $horario->dia )][] = $horario->hr;
        }
        return empty($dia) ? $arrHorarios : ( !empty($arrHorarios[($dia)]) ? $arrHorarios[($dia)] : [] );
    }

    public function horariosOcupados($dia=0) {
        $arrOcupado = array();

        foreach( $this->teachclasses AS $clase ) {

            $arr2 = $clase->getHorarioArray();
            foreach( $arr2 AS $_dia=>$arrHr) {
                if ( empty($arrOcupado[$_dia]) || !is_array( $arrOcupado[$_dia]) ) {
                    $arrOcupado[$_dia] = $arrHr;
                }
                else {
                    $arrOcupado[$_dia] = array_merge( $arrOcupado[$_dia], $arrHr );
                }
            }
        }
        return empty($dia) ? $arrOcupado : ( !empty($arrOcupado[$dia]) ? $arrOcupado[$dia] : [] );
    }

    public function horariosDisponibles($dia=false) {

        $arrHorarios = $this->getHorarioArray();
        $arrOcupado = $this->horariosOcupados();

        foreach( $arrOcupado AS $_dia=>$arrHrs ) {
            foreach($arrHrs AS $hr) {
                if ( !empty($arrHorarios[($_dia)]) && ($rmvkey = array_search($hr, $arrHorarios[($_dia)]) ) !== false ) {
                    unset($arrHorarios[($_dia)][$rmvkey]);
                }
            }
        }
        foreach($arrHorarios AS $_dia=>$arrHrs) {
            if ( empty($arrHrs) ) {
                unset($arrHorarios[$_dia]);
            }
        }
        return empty($dia) ? $arrHorarios : ( !empty($arrHorarios[( $dia )]) ? $arrHorarios[( $dia )] : [] );
    }

    /**
     * Actualiza los horarios eliminando todos los anteriores
     * @param array horarios
     * @return array
     */
    public function saveHorarios($horarios) {
        if (empty($horarios) || !is_array($horarios) ){
            return false;
        }

        $deleted = \Illuminate\Support\Facades\DB::delete('DELETE FROM user_horarios WHERE user_id='.$this->id);

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

    /**
     * Save profile pic
     * @param UploadedFile $file
     * @return bool|string failure|filename
     */
    public function saveProfilePic(\Illuminate\Http\UploadedFile $file) {
        if ( !is_a($file, 'Illuminate\Http\UploadedFile') ) {
            dd('NO ES Illuminate\Http\UploadedFile');
            return false;
        }

        switch( $this->user_type ) {
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
     * @param int $size
     * @return string asset url
     */
    public function getProfilePic($size=80) {
        $fileName = $this->getMeta('profilepic');
        if ( empty($fileName) ) {
            return false;
        }

        switch( $this->user_type ) {
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

        if ($size===80 || !is_int($size)) {
            return asset( $filePath.'/'.$fileName );
        }

        $fileName = preg_replace('/\-80x80\./', '-'.$size.'x'.$size.'.', $fileName);

        return asset( $filePath.'/'.$fileName );
    }
}
