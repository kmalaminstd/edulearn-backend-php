<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require './src/middlewares/AuthMiddleware.php';
    require './src/controllers/EnrollCourse.php';
    require './src/config/Database.php';

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        http_response_code(403);
        echo json_encode(['message' => 'Method not allowed']);
        exit;
    }

    $header = getallheaders();

    if(!$header || !$header['Authorization'] || !$header['student_id']){
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

    $course_id = filter_input(INPUT_POST, 'course_id' , FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $student_id = $header['student_id'];
    $payment = true;

    $data = ['course_id' => $course_id, 'student_id' => $student_id, 'payment' => $payment];

    // echo $data;

    $enrollCoursCls = new EnrollCourse($db);
    $resp = $enrollCoursCls->enroll($data);


    echo $resp->response();

    

?>