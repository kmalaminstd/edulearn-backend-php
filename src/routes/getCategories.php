<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require './src/config/Database.php';
    require './src/controllers/GetAllCategories.php';


    // verify method
    if($_SERVER['REQUEST_METHOD'] !== 'GET'){

        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
        exit;

    }

    $database = new Database();
    $db = $database->connect();

    $cateCls = new GetCategories($db);
    $resp = $cateCls->getCategory();

    echo $resp->response();


?>