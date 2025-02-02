<?php

    // the learner will get the course list what they enrolled

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require './src/config/Database.php';
    require './src/middlewares/AuthMiddleware.php';
    require './src/controllers/GetMyEnrolledCourse.php';

    // verify method
    if($_SERVER['REQUEST_METHOD'] !== 'GET'){
        http_response_code(403);
        echo json_encode(['message' => 'Method not allowed']);
        exit;
    }

    // verifying headers
    $headers = getallheaders();
    $token = trim(str_replace('Bearer ' , '' , $headers['Authorization']));
    $user_id = $headers['user_id'];

    if(!isset($headers['Authorization'] , $headers['user_id'])){
        http_response_code(400);
        echo json_encode(['message' => 'headers is missing']);
        exit;
    }
    

    // verifying auth
    $auth = new AuthMiddleware();
    if(!$auth->hasRole($token, 'student')){
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized user']);
        exit;
    }

    $database = new Database();
    $db = $database->connect();

    $data = ['user_id' => $user_id];

    $myEnrollCls = new GetMyEnrollCourse($db);
    $resp = $myEnrollCls->myCourse($data);

    echo $resp->response();

?>