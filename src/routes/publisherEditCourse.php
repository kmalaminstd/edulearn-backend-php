<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require './src/config/Database.php';
    require './src/controllers/PublisherCourseManage.php';
    require './src/middlewares/AuthMiddleware.php';

    // verify method
    if($_SERVER['REQUEST_METHOD'] != 'POST'){
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
    if(!$auth->hasRole($token ,'teacher')){
        http_response_code(400);
        echo json_encode(['message' => 'Unauthorized user']);
        exit;
    }

    // database
    $database = new Database();
    $db = $database->connect();

    // getting data
    $title = filter_input(INPUT_POST, 'title' , FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description' , FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category = filter_input(INPUT_POST, 'category' , FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price' , FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $data = [
        'id' => $headers['course_id'],
        'title' => $title,
        'description' => $description,
        'category' => $category,
        'price' => $price
    ];

    print_r($data['title']);

    // validating data
    if($title === null || $description === null || $category === null || $price === ''){
        http_response_code(400);
        echo json_encode(['message' => 'Invalid field']);
        exit;
    }

    // upate process
    $manageCourse = new PublisherCourseManage($db);
    $res = $manageCourse->updateCourse($data);

    echo $res->response();


?>