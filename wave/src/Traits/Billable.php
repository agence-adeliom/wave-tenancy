<?php

namespace Wave\Traits;

use Wave\Subscription;
use Wave\Plan;

trait Billable {

    public function onTrial()
    {
        if (is_null($this->trial_ends_at)) {
            return false;
        }
        if ($this->subscriber()) {
            return false;
        }
        return true;
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'billable_id')->where('billable_type', config('wave.billing_type', 'user'));
    }

    public function subscriber()
    {
        return $this->subscriptions()->where('status', 'active')->exists();
    }

    public function subscribedToPlan($planSlug)
    {
        $plan = Plan::where('name', $planSlug)->first();
        if (!$plan) {
            return false;
        }
        return $this->subscriptions()->where('plan_id', $plan->id)->where('status', 'active')->exists();
    }

    public function plan(){
        $latest_subscription = $this->latestSubscription();
        return Plan::find($latest_subscription->plan_id);
    }

    public function planInterval(){
        $latest_subscription = $this->latestSubscription();
        return ($latest_subscription->cycle == 'month') ? 'Monthly' : 'Yearly';
    }

    public function latestSubscription()
    {
        return $this->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first();
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'billable_id')->where('status', 'active')->orderBy('created_at', 'desc');
    }

    public function invoices(){
        $billable_invoices = [];

        if(is_null($this->subscription)){
            return null;
        }

        if(config('wave.billing_provider') == 'stripe'){
            $stripe = new \Stripe\StripeClient(config('wave.stripe.secret_key'));
            $subscriptions = $this->subscriptions()->get();
            foreach($subscriptions as $subscription){
                $invoices = $stripe->invoices->all([ 'customer' => $subscription->vendor_customer_id, 'limit' => 100 ]);

                foreach($invoices as $invoice){
                    array_push($billable_invoices, (object)[
                        'id' => $invoice->id,
                        'created' => \Carbon\Carbon::parse($invoice->created)->isoFormat('MMMM Do YYYY, h:mm:ss a'),
                        'total' => number_format(($invoice->total /100), 2, '.', ' '),
                        'download' => $invoice->invoice_pdf
                    ]);
                }
            }
        } else {
            $paddle_url = (config('wave.paddle.env') == 'sandbox') ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';
            $response = Http::withToken(config('wave.paddle.api_key'))->get($paddle_url . '/transactions', [
                'subscription_id' => $this->subscription->vendor_subscription_id
            ]);
            $responseJson = json_decode($response->body());
            foreach($responseJson->data as $invoice){
                array_push($billable_invoices, (object)[
                    'id' => $invoice->id,
                    'created' => \Carbon\Carbon::parse($invoice->created_at)->isoFormat('MMMM Do YYYY, h:mm:ss a'),
                    'total' => number_format(($invoice->details->totals->subtotal /100), 2, '.', ' '),
                    'download' => '/settings/invoices/' . $invoice->id
                ]);
            }
        }

        return $billable_invoices;
    }
}
