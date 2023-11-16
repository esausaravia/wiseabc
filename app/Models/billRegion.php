<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class billRegion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'countries'];

    /**
     * RelationShips
     */
    public function billingPlans()
    {
        return $this->hasMany(BillingPlan::class);
    }
}
