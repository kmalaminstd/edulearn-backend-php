<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require "./src/controllers/AdminLogin.php";
    require "./src/config/Database.php";


    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        http_response_code(403);
        echo json_encode(['message' => "Method not allowed"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"));

    if(!$data || !isset($data->email, $data->password)){
        http_response_code(400);
        echo json_encode(["message" => "Invalid field"]);
        exit;
    }

    if(json_last_error() !== JSON_ERROR_NONE){
        http_response_code(400);
        echo json_encode(["message" => "Invalid json"]);
        exit;
    }

    $database = new Database();
    $db = $database->connect();

    $adminLog = new AdminLogin($db);
    $response = $adminLog->adminLogin($data);

    echo $response->response();


?>