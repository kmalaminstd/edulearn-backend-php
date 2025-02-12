<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require './src/middlewares/AuthMiddleware.php';
    require './src/controllers/AdCourseList.php';
    require './src/config/Database.php';

 
    // verify method
    if($_SERVER['REQUEST_METHOD'] !== 'GET'){
        http_response_code(403);
        echo json_encode(['message' => 'Method not allowed']);
        exit;
    }

    // verify headers
    $header = getallheaders();

    if(!$header || !$header['Authorization'] || !$header['page']){
        http_response_code(400);
        echo json_encode(['message' => 'Headers error']); 
        exit;
    }

    // verify authentication
    $token = trim(str_replace('Bearer' , '', $header['Authorization']));

    $auth = new AuthMiddleware();
    if(!$auth->hasRole($token, 'admin')){
        http_response_code(403);
        echo json_encode(['message' => 'Unauthorized user']);
        exit;
    }

    // page
    $pageNumber = $header['page'];

    // database
    $database = new Database();
    $db = $database->connect();

    // process
    $courseList = new AdCourseList($db, $pageNumber);
    $res = $courseList->readList();

    echo $res->response();

?>