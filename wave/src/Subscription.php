<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'billable_type',
        'billable_id',
        'plan_id',
        'vendor_slug',
        'vendor_product_id',
        'vendor_transaction_id',
        'vendor_customer_id',
        'vendor_subscription_id',
        'cycle',
        'status',
        'seats',
        'trial_ends_at',
        'ends_at',
        'last_payment_at',
        'next_payment_at',
        'cancel_url',
        'update_url'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'cancelled_at' => 'datetime',
        'last_payment_at' => 'datetime',
        'next_payment_at' => 'datetime',
    ];

    /**
     * The user that owns the subscription.
     */
    public function subscriber()
    {
        $model = match (config('wave.billing_type', 'user')) {
            'workspace' => config('wave.workspace_model', 'App\Models\Workspace'),
            default => config('wave.user_model', 'App\Models\User'),
        };
        return $this->belongsTo($model, 'billable_id');
    }


    public function cancel(){
        $this->status = 'cancelled';
        $this->save();

        $this->user->syncRoles([]);
        $this->user->assignRole( config('wave.default_user_role', 'registered') );
    }

    /**
     * The plan that belongs to the subscription.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

}
