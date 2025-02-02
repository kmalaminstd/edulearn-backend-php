<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require "./src/controllers/Login.php";
    require "./src/config/Database.php";

   try{

        // verify the method
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){

            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
            exit;

        }

        // verify data
        $data = json_decode(file_get_contents("php://input"));

        if(!$data){

            http_response_code(400);
            echo json_encode(["message" => "Invalid field"]);
            exit;

        }

        // handling json error
        if(json_last_error() !== JSON_ERROR_NONE){

            http_response_code(400);
            echo json_encode(["message" => "Invalid field"]);
            exit;

        }

        // login process
        $database = new Database();
        $db = $database->connect();
        $login = new Login($db);
        $res = $login->userLogin($data);

        $response = $res->response();

        echo $response;


   }catch(Exception $e){
    
        http_response_code(500);
        echo json_encode([
            "message" => "Server error",
            'error' => $e->getMessage()
        ]);

   }

    

?>