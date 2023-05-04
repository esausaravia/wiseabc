<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class BillingPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name','tipo','ritmo','price'];

    /**
     * Relationships
     */
    public function users()
    {
        return $this->belongsToMany( User::class, 'subscriptions', 'billing_plan_id', 'user_id' )->as('subscription')->withTimestamps()->withPivot('status')->orderByPivot('created_at', 'desc');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class)->with('user');
    }

    public function paypal()
    {
        return $this->morphOne(Paypalobj::class, 'paypalable')->ofMany([
            'created_at'=>'max',
            'id'=>'max'
        ], function($query){
            $query->where('api','paypal');
        });

        return $this->morphOne(Paypalobj::class, 'paypalable');
    }

    public function stripe()
    {
        return $this->morphOne(Paypalobj::class, 'paypalable')->ofMany([
            'created_at'=>'max',
            'id'=>'max'
        ], function($query){
            $query->where('api','stripe');
        });
    }


    /**
     * Accesors
     */
    protected function ritmoLabel(): Attribute
    {
        return Attribute::make(
            get: function($value, $attributes) {
                $config = config('wiseabc.ritmo_labels');
                if (!is_array($config) ) {
                    $config = array();
                }
                return $this->ritmo!==NULL && !empty($config[( $this->ritmo )]) ? $config[( $this->ritmo )] : $this->ritmo;
            },
        );
    }
}
