<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require './src/payment_gateway/stripe.php';
    require './src/middlewares/AuthMiddleware.php';

    // verify method
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        http_response_code(403);
        echo json_encode(['message' => 'Method not allowed']);
        exit;
    }

    // verifying headers
    $headers = getallheaders();
    if(!isset($headers['Authorization'])){
        http_response_code(400);
        echo json_encode(['message' => 'headers is missing']);
        exit;
    }
    $token = trim(str_replace('Bearer' , '' , $headers['Authorization']));

    // verifying auth
    $auth = new AuthMiddleware();
    if(!$auth->hasRole($token, 'student')){
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized user']);
        exit;
    }

    // getting data
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cancel_url = filter_input(INPUT_POST, 'cancel_url', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $success_url = filter_input(INPUT_POST, 'success_url', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(!$price || !$title || !$cancel_url || !$success_url){
        http_response_code(400);
        echo json_encode(['message' => 'Invalid data']);
        exit;
    }

    // payment url
    $stripeCls = new StripePayment();
    $url = $stripeCls->stripePay($price, $title, $success_url , $cancel_url);
    echo $url;

?>