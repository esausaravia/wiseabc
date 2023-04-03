<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamsInfo extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['msid','link','info','report'];

    /**
     * Relationships
     */

    public function schedules(){
        return $this->hasMany(Schedule::class, 'teams_id', 'id');
    }
}
