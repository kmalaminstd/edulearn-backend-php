<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require './src/middlewares/AuthMiddleware.php';
    require './src/config/Database.php';
    require './src/controllers/GetAllStudentUser.php';

    // verify method
    if($_SERVER['REQUEST_METHOD'] !== 'GET'){
        http_response_code(403);
        echo json_encode(['message' => 'Method not allowed']);
        exit;
    }

    //verify header
    $getAllHeaders = getallheaders();
    if(!isset($getAllHeaders['Authorization']) || !isset($getAllHeaders['page'])){
        http_response_code(400);
        echo json_encode(['message' => 'Header is missing']);
        exit;
    }

    // verify auth

    $token = str_replace( 'Bearer', '', $getAllHeaders['Authorization']);
    $authMid = new AuthMiddleware();
    if(!$authMid->hasRole(trim($token), 'admin')){
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized']);
        exit;
    }

    $database = new Database();
    $db = $database->connect();

    $page = $getAllHeaders['page'];
    $stdUser = new GetAllStudentUsers($db, $page);
    $res = $stdUser->studentUser();

    echo $res->response();

?>