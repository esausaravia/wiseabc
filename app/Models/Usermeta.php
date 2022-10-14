<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usermeta extends Model
{
    use HasFactory;

    public $timestamps=false;
    protected $fillable = ['metakey','metaval'];

    /**
    * All of the relationships to be touched.
    *
    * @var array
    */
    protected $touches = ['user'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
