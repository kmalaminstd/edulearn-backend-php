<?php

    // when a user already enrolled the course and will see the course video then from here will get the info 


    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require './src/middlewares/AuthMiddleware.php';
    require './src/controllers/UserSelectedCouseToShow.php';
    require './src/config/Database.php';

    if($_SERVER['REQUEST_METHOD'] !== 'GET'){
        http_response_code(403);
        echo json_encode(['message' => 'Method not allowed']);
        exit;
    }

    $header = getallheaders();


    if(!$header || !isset($header['Authorization'], $header['uid'], $header['cid'])){
        http_response_code(400);
        echo json_encode(['message' => 'Headers error']);
        exit;
    }

    $token = trim(str_replace('Bearer', '', $header['Authorization']));
    $user_id = trim($header['uid']);
    $course_id = trim($header['cid']);

    $auth = new AuthMiddleware();
    if(!$auth->hasRole($token, 'student')){
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized users']);
        exit;
    }

    $database = new Database();
    $db = $database->connect();

    $data = json_decode(json_encode(['user_id' => $user_id, 'course_id' => $course_id]));

    $userCls = new UserSelectedCourseToShow($db);
    $resp = $userCls->selectedCourse($data);

    echo $resp->response();

?>