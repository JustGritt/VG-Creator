<?php
namespace App\Controller;
//session_start();

use App\Core\CleanWords;
use App\Core\Sql;
use App\Core\Verificator;
use App\Core\View;
use App\Model\User as UserModel;
use App\Core\Mail;
use App\Model\PasswordRecovery as Recovery; 

require_once('libs/stripe-php/init.php');

class Payment {


    public function payment(){

        $view = new View("payment");
        $user = new User();
        $view->assign("user", $user);

        \Stripe\Stripe::setApiKey('sk_test_VePHdqKTYQjKNInc7u56JBrQ');

        header('Content-Type: application/json');

        $YOUR_DOMAIN = 'http://localhost/';

        $checkout_session = \Stripe\Checkout\Session::create([
        'line_items' => [[
            # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
            'price' => '10000',
            'quantity' => 1,
        ]],
        'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.view.php',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.view.php',
        ]);
        /*
        $stripe = new \Stripe\StripeClient('sk_test_BQokikJOvBiI2HlWgH4olfQ2');
        $customer = $stripe->customers->create([
            'description' => 'Charles',
            'email' => 'charles@example.com',
            'payment_method' => 'pm_card_visa',
        ])
        */
        echo $customer;
    }



    public function test(){

        // This is a public sample test API key.
        // Don’t submit any personally identifiable information in requests made with this key.
        // Sign in to see your own test API key embedded in code samples.
        \Stripe\Stripe::setApiKey('sk_test_VePHdqKTYQjKNInc7u56JBrQ');

        header('Content-Type: application/json');

        $YOUR_DOMAIN = 'http://localhost/';

        $checkout_session = \Stripe\Checkout\Session::create([
        'line_items' => [[
            # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
            'price' => '{{PRICE_ID}}',
            'quantity' => 1,
        ]],
        'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.view.php',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.view.php',
        ]);

        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);
    }
}