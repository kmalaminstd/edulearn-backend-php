<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require './src/config/Database.php';
    require './src/controllers/PublisherCourseManage.php';
    require './src/middlewares/AuthMiddleware.php';

    // verify method
    if($_SERVER['REQUEST_METHOD'] != 'DELETE'){
        http_response_code(400);
        json_encode(['message' => 'Method not allowed']);
        exit;
    }

    // verify header
    $headers = getallheaders();

    if(!$headers['Authorization'] || !$headers['course_id']){
        http_response_code(400);
        echo json_encode(['message' => 'Header is missing']);
        exit;
    }

    // verify auth
    $token = trim(str_replace('Bearer', '', $headers['Authorization']));
    $auth = new AuthMiddleware();
    if(!$auth->hasRole($token ,'admin')){
        http_response_code(400);
        echo json_encode(['message' => 'Unauthorized user']);
        exit;
    }

    // database
    $database = new Database();
    $db = $database->connect();

    $data = ['id' => $headers['course_id']];

    // delete process
    $manageCourse = new PublisherCourseManage($db);
    $res = $manageCourse->deleteCourse($data);

    echo $res->response();

?>