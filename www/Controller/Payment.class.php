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

        //$view = new View("product" , 'payment');
        //$user = new User();
        //$view->assign("user", $user);

        \Stripe\Stripe::setApiKey('sk_test_51KWK7NKk8eBsPmrsFxgEhYtLHITTT9Mks1lnwrwdtEdkQ5rMJB8llvUHVxk84ys262tBVGvgkMk1t71BT2nIilf900Roq5sRyD');

        header('Content-Type: application/json');

        $YOUR_DOMAIN = 'http://localhost/';
        $stripe = new \Stripe\StripeClient('sk_test_51KWK7NKk8eBsPmrsFxgEhYtLHITTT9Mks1lnwrwdtEdkQ5rMJB8llvUHVxk84ys262tBVGvgkMk1t71BT2nIilf900Roq5sRyD');
        $customer = $stripe->customers->create([
            'description' => 'Charles',
            'email' => 'charles@example.com',
            'payment_method' => 'pm_card_visa',
        ]);


        $checkout_session = \Stripe\Checkout\Session::create([
        'line_items' => [[
        # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
        'price' => 'price_1KXC12Kk8eBsPmrsBljaxi7X',
        'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN . 'view/success.view.php',
        'cancel_url' => $YOUR_DOMAIN . 'view/cancel.view.php',
        'automatic_tax' => [
        'enabled' => true,
        ],
        ]);

        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);

        //echo $customer;
    }
}