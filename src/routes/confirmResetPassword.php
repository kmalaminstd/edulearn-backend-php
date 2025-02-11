<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require "./src/controllers/PasswordReset.php";
    require "./src/config/Database.php";

    // verify method
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        http_response_code(403);
        echo json_encode(['message' => 'Method not allowed']);
        exit;
    }

    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // verify data
    if(!$password || !$token ){
        echo json_encode(['message' => 'Invalid information']);
        exit;
    }

    $data = [
        'token' => $token,
        'password' => $password
    ];

    $database = new Database();
    $db = $database->connect();

    // verify confirm password process
    $reset_password = new PasswordReset($db);
    $res = $reset_password->confirm_password($data);

    echo $res->response();

?>