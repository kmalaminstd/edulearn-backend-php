<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require './src/middlewares/AuthMiddleware.php';
    require './src/controllers/UseredShowedCourseList.php';
    require './src/config/Database.php';

    if($_SERVER['REQUEST_METHOD'] !== 'GET'){
        http_response_code(403);
        echo json_encode(['message' => 'Method not allowed']);
        exit;
    }

    $header = getallheaders();


    if(!$header || !$header['Authorization'] || !$header['page']){
        http_response_code(400);
        echo json_encode(['message' => 'Headers error']);
        exit;
    }
    $database = new Database();
    $db = $database->connect();

    $token = trim(str_replace('Bearer', '', $header['Authorization']));

    $auth = new AuthMiddleware();
    if(!$auth->hasRole($token, 'student')){
        http_response_code(401);
        echo json_encode(['message' => 'Unauthenticated user']);
        exit;
    }

    $page = $header['page'];

    $userShowedCourseCls = new UseredShowedCourseList($db, $page);
    $resp = $userShowedCourseCls->courseList();

    echo $resp->response();
    
?>