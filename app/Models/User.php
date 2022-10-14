<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

	public function usermetas() {
		return $this->hasMany(\App\Models\Usermeta::class);
	}

	/**
	 * @param string $mkey
	 * @return \App\Models\ColeccionMeta|false|mixed
	 */
	public function getMeta($mkey="")
	{
		if ( empty($mkey) || empty($this->usermetas) || ! is_object($this->usermetas)
			|| ! is_a($this->usermetas, "Illuminate\Database\Eloquent\Collection") )
		{
			return false;
		}

        $return = '';

		foreach( $this->usermetas AS $meta )
		{
			if ($meta->metakey===$mkey || $meta->id===$mkey)
			{
				$return .= $meta->metaval;
			}
		}
		return $return!=='' ? $return : false;
	}

    public function getAttrs() {

        return $this->attributes;
    }

	public function __get($gkey)
	{
		if ( isset($this->attributes[$gkey]) )
        {
			return parent::__get($gkey);
		}
        $meta = $this->getMeta($gkey);
        return !empty($meta) ? $meta : parent::__get($gkey);
	}
}
