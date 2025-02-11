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

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(!$email){
        http_response_code(400);
        echo json_encode(['message' => 'Invalid email', 'email' => $email]);
        exit;
    }

    $data = ['email' => $email];


    $database = new Database();
    $db = $database->connect();

    $password = new PasswordReset($db);
    $res = $password->reset_request($data);

    echo $res->response();


?>