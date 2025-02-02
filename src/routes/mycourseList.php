<?php

    // from here the publishers who publish their courses will get the full course list

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require './src/config/Database.php';
    require './src/middlewares/AuthMiddleware.php';
    require './src/controllers/GetMyCourseList.php';

    // verify method
    if($_SERVER['REQUEST_METHOD'] !== 'GET'){
        http_response_code(403);
        echo json_encode(['message' => 'Method not allowed']);
        exit;
    }

    // verifying headers
    $headers = getallheaders();
    if(!isset($headers['Authorization'] , $headers['user_id'])){
        http_response_code(400);
        echo json_encode(['message' => 'headers is missing']);
        exit;
    }
    $token = trim(str_replace('Bearer' , '' , $headers['Authorization']));
    $user_id = $headers['user_id'];

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

    // getting the class from controller
    $myCourseCls = new GetMyCourseList($db);
    $resp = $myCourseCls->getList($user_id);

    echo $resp->response();

?>