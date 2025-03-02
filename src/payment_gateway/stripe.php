<?php

    class StripePayment{

        private $stripe;

        private $stripe_session;


        public function __construct(){
            $this->stripe = new \Stripe\StripeClient($_ENV['STRIPE_SECRET_KEY']);
        }

        public function stripePay($price, $product_name, $success_url, $cancel_url){

            $this->stripe_session = $this->stripe->checkout->sessions->create([
                'mode' => 'payment',
                'success_url' => $success_url,
                'cancel_url' => $cancel_url,
                'customer_email' => 'alamnikhanstd@gmail.com',
                'invoice_creation' => [
                    'enabled' => true,
                ],
                'payment_method_types' => [
                    'card'
                ],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'unit_amount' => $price,
                            'product_data' => [
                                'name' => $product_name
                            ]
                        ],
                        'quantity' => 1
                    ]
                ],
                
            ]);

            return $this->stripe_session['url'];
        }
    
    }


?>