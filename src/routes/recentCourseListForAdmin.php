<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require "./src/controllers/AdRecentCourses.php";
    require "./src/config/Database.php";
    require "./src/middlewares/AuthMiddleware.php";

    // verify method
    if($_SERVER['REQUEST_METHOD'] !== 'GET'){
        http_response_code(403);
        echo json_encode(['message' => "Method not allowed"]);
        exit;
    }

    // verify header
    $headers = getallheaders();

    if(!$headers || !$headers['Authorization']){
        http_response_code(400);
        echo json_encode(['message' => 'Header is missing']);
        exit;
    }

    // verify authentication
    $token = trim(str_replace('Bearer', '', $headers['Authorization']));
    $auth = new AuthMiddleware();
    if(!$auth->hasRole($token, 'admin')){
        http_response_code(403);
        echo json_encode(['message' => 'Unauthorized user']);
        exit;
    }

    // database
    $database = new Database();
    $db = $database->connect();

    $courseList = new AdRecentCourses($db);
    $res = $courseList->readRecCourse();

    echo $res->response();

?>