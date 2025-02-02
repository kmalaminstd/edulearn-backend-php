<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST,GET");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require "./src/controllers/VideoController.php";
    require './src/middlewares/AuthMiddleware.php';
    require './src/config/Database.php';

    try{

        $headers = getallheaders();
        $token = isset($headers['Authorization']) ? str_replace('Bearer', '' , $headers['Authorization']) : null;

        $auth = new AuthMiddleware();
        if(!$auth->hasRole($token, ['teacher'])){
            http_response_code(403);
            echo json_encode(['message' => 'Unauthorized user']);
            exit();
        }

        $database = new Database();
        $db = $database->connect();

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
            if(json_last_error() !== JSON_ERROR_NONE){
                http_response_code(400);
                echo json_encode(['message' => 'Json error']);
            }
            
            $data = json_decode(file_get_contents('php://input'));

            $videController = new VideoController($db);
            echo $videController->videoUpload($data, $decoded->user_id);
            
        }

    }catch(Exception $e){
        http_response_code(500);
        echo json_encode(['message' => 'Server' , 'err' => $e]);
    }


?>