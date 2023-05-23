<?php

namespace App\Models;

use App\Http\Controllers\PayPalController;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id','billing_plan_id','status','start','next_billing'];

    protected $casts = [
      'start'=>'datetime',
      'next_billing'=>'datetime'
    ];

    /**
     * Relationships
     */
    public function user()
    {
      return $this->belongsTo(User::class);
    }

    public function billingPlan()
    {
      //                     (BillingPlan::class, 'billing_plan_id', 'id');
      return $this->belongsTo(BillingPlan::class);
    }

    public function paypal(){
        return $this->morphOne(Paypalobj::class, 'paypalable')->ofMany([
          'created_at'=>'max',
          'id'=>'max'
        ], function($query){
          $query->where('api','like','paypal');
        });
    }

    public function stripe(){
        return $this->morphOne(Paypalobj::class, 'paypalable')->ofMany([
          'created_at'=>'max',
          'id'=>'max'
        ], function($query){
          $query->where('api','stripe');
        });
    }


    /**
     * Methods
     */
}
/*
Paypal Subscription details:
https://developer.paypal.com/docs/api/subscriptions/v1/#subscriptions_get

{
  "id": "I-BW452GLLEP1G",
  "plan_id": "P-5ML4271244454362WXNWU5NQ",
  "start_time": "2019-04-10T07:00:00Z",
  "quantity": "20",
  "shipping_amount": {
    "currency_code": "USD",
    "value": "10.0"
  },
  "subscriber": {
    "shipping_address": {
      "name": {
        "full_name": "John Doe"
      },
      "address": {
        "address_line_1": "2211 N First Street",
        "address_line_2": "Building 17",
        "admin_area_2": "San Jose",
        "admin_area_1": "CA",
        "postal_code": "95131",
        "country_code": "US"
      }
    },
    "name": {
      "given_name": "John",
      "surname": "Doe"
    },
    "email_address": "customer@example.com",
    "payer_id": "2J6QB8YJQSJRJ"
  },
  "billing_info": {
    "outstanding_balance": {
      "currency_code": "USD",
      "value": "1.0"
    },
    "cycle_executions": [
      {
        "tenure_type": "TRIAL",
        "sequence": 1,
        "cycles_completed": 0,
        "cycles_remaining": 2,
        "total_cycles": 2
      },
      {
        "tenure_type": "TRIAL",
        "sequence": 2,
        "cycles_completed": 0,
        "cycles_remaining": 3,
        "total_cycles": 3
      },
      {
        "tenure_type": "REGULAR",
        "sequence": 3,
        "cycles_completed": 0,
        "cycles_remaining": 12,
        "total_cycles": 12
      }
    ],
    "last_payment": {
      "amount": {
        "currency_code": "USD",
        "value": "1.15"
      },
      "time": "2019-04-09T10:27:20Z"
    },
    "next_billing_time": "2019-04-10T10:00:00Z",
    "failed_payments_count": 0
  },
  "create_time": "2019-04-09T10:26:04Z",
  "update_time": "2019-04-09T10:27:27Z",
  "links": [
    {
      "href": "https://api-m.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G/cancel",
      "rel": "cancel",
      "method": "POST"
    },
    {
      "href": "https://api-m.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G",
      "rel": "edit",
      "method": "PATCH"
    },
    {
      "href": "https://api-m.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G",
      "rel": "self",
      "method": "GET"
    },
    {
      "href": "https://api-m.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G/suspend",
      "rel": "suspend",
      "method": "POST"
    },
    {
      "href": "https://api-m.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G/capture",
      "rel": "capture",
      "method": "POST"
    }
  ],
  "status": "ACTIVE",
  "status_update_time": "2019-04-09T10:27:27Z"
}
*/