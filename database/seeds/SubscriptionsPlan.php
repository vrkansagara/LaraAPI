<?php

use Illuminate\Database\Seeder;

class SubscriptionsPlan extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stripeToken = 'sk_test_fDhV0Lk2WyXCL9ujJiPfi53t';
        $stripe = new \Stripe\Stripe();
        $stripe->setApiKey($stripeToken);
        $stripePlans = new \Stripe\Plan();
        $plan = $stripePlans->create([
            'id'                   => 'monthly',
            'name'                 => 'Monthly (30$)',
            'amount'               => 30.00,
            'currency'             => 'USD',
            'interval'             => 'month',
            'statement_descriptor' => 'Monthly Subscription to Foo Bar Inc.',
        ]);

        echo $plan['id'];
    }
}
