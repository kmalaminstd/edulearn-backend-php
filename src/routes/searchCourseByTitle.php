<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require "./src/controllers/SearchCourse.php";
    require "./src/config/Database.php";

    // verify method
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        http_response_code(400);
        json_encode(['message' => 'Method not allowed']);
        exit;
    }

    // verify header
    $headers = getallheaders();

    if(!$headers['page']){
        http_response_code(400);
        echo json_encode(['message' => 'Header is missing']);
        exit;
    }

    // getting data
    $page = $headers['page'];
    $searchText = filter_input(INPUT_POST, 'searchText', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(!$searchText){
        http_response_code(400);
        echo json_encode(['message' => 'Invalid text']);
        exit;
    }

    $database = new Database();
    $db = $database->connect();

    $data = ['searchText' => $searchText];

    // search process
    $courseSearch = new SearchCourse($db, $page);
    $res = $courseSearch->searchByTitle($data);

    echo $res->response();

?>