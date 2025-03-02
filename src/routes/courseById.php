<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require './src/config/Database.php';
    require './src/controllers/GetCourseById.php';
    require './src/middlewares/AuthMiddleware.php';

    // verify method
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        http_response_code(403);
        echo json_encode(['message' => 'Method not allowed']);
        exit;
    }

    // verify header
    $headers = getallheaders();

    if(!$headers['Authorization']){
        http_response_code(400);
        echo json_encode(['message' => 'Header is missing']);
        exit;
    }

    $token = trim(str_replace('Bearer', '', $headers['Authorization']));

    // verify authentication
    $auth = new AuthMiddleware();
    if(!$auth->hasRole($token, 'student')){
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized user']);
        exit;
    }

    // verify data
    $id = filter_input(INPUT_POST, 'course_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(!$id){
        http_response_code(400);
        echo "Id not found ID: $id";
        exit;
    }

    // dbs
    $database = new Database();
    $db = $database->connect();

    

    $courseCls = new GetCourseById($db);
    $res = $courseCls->getCourse($id);

    echo $res->response();


?>