<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require './src/config/Database.php';
    require './src/controllers/UserActivity.php';
    require './src/middlewares/AuthMiddleware.php';

    // verify method
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        http_response_code(403);
        echo json_encode(['message' => 'Method not allowed']);
        exit;
    }

    $database = new Database();
    $db = $database->connect();

    $headers = getallheaders();
    if(!isset($headers['Authorization']) || !isset($headers['user_id']) || !isset($headers['role'])){
        http_response_code(400);
        echo json_encode(['message' => 'Header is missing']);
        exit;
    }

    $token = trim(str_replace('Bearer', '' , $headers['Authorization']));

    // verify authentication
    $auth = new AuthMiddleware();
    if(!$auth->hasRole($token, $headers['role'])){
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized user']);
        exit;
    }

    $data = ['id' => $headers['user_id']];

    if(!isset($data)){
        http_response_code(400);
        echo json_encode(['message' => 'Data is not coreect']);
        exit;
    }

    $userAct = new UserActivity($db);
    $resp = $userAct->setLastActivity($data);

    echo $resp->response();
?>