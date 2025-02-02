<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, user_id");

    require './src/helpers/JWThelper.php';
    require './src/controllers/GetUserInfo.php';
    require './src/config/Database.php';

    if($_SERVER['REQUEST_METHOD'] !== 'GET'){
        http_response_code(403);
        echo json_encode(['message' => 'Method not allowed']);
    }

    if(json_last_error() !== JSON_ERROR_NONE){
        http_response_code(400);
        echo json_encode(['message' => 'Data error']);
    }

    $header = getallheaders();

    // echo json_encode(['message' => $header['Authorization']]);

    if(!$header || !$header['Authorization'] || !$header['user_id']){
        http_response_code(400);
        echo json_encode(['message' => 'Headers error']);
    }
    $database = new Database();
    $db = $database->connect();

    $token = str_replace('Bearer', '', $header['Authorization']);

    $jwt = new JWThelper();
    $userInf = $jwt->tokenVerify(trim($token));


    if(!$userInf){
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized']);
    }

    $userCs = new GetUserInfo($db);
    $res = $userCs->getInfo(['id' => $header['user_id']]);
    echo $res->response();

    // echo json_encode(['msg' => $userInf]);
      

?>