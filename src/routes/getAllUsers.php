<?php

    require './src/middlewares/AuthMiddleware.php';
    require './src/controllers/GetAllUsers.php';
    require './src/config/Database.php';

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    if($_SERVER['REQUEST_METHOD'] !== 'GET'){
        http_response_code(403);
        echo json_encode(['message' => 'Method not allowed']);
        exit;
    }

    $header = getallheaders();

    $token = str_replace('Bearer' , '' , $header['Authorization']);
    
    $authMid = new AuthMiddleware();
    $auth = $authMid->hasRole(trim($token), 'admin');
    
    if(!$auth){
        http_response_code(401);
        echo json_encode(['message' => 'Unauth­orized']);
        exit;
    }

    $database = new Database();
    $db = $database->connect();

    $getUser = new GetAllUsers($db);
    $res = $getUser->usersList();

    echo $res->response();


?>