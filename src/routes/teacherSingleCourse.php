<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require './src/config/Database.php';
    require './src/middlewares/AuthMiddleware.php';
    require './src/controllers/GetSingleCourseView.php';

    // verify method
    if($_SERVER['REQUEST_METHOD'] !== 'GET'){
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
    if(!$auth->hasRole($token, 'teacher')){
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized user']);
        exit;
    }

    // database
    $database = new Database();
    $db = $database->connect();

    $user_id = isset($_GET['id']) ? $_GET['id'] : null ;
    $teacher_id =   isset($_GET['tid']) ? $_GET['tid'] : null;

    if(!$user_id || !$teacher_id){
        http_response_code(400);
        echo json_encode(['message' => 'Insufficient information' , 'data' => ['id' => $user_id , 'teacher_id' => $teacher_id]]);
        exit;
    }

    $courseViewCls = new GetSingleCourseView($db);
    $resp = $courseViewCls->getCourseView($user_id, $teacher_id);

    echo $resp->response();


?>