<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require "./src/controllers/Registration.php";
    require "./src/config/Database.php";
    

    try{ 

        // verify request method
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
            exit();
        }

        // get data request
        $data = json_decode(file_get_contents("php://input"));

        if(json_last_error() !== JSON_ERROR_NONE){
            http_response_code(400);
            echo json_encode(["message" => "Invalid json", "error" => json_last_error_msg() , "data" => $data]);
        }

        // validate input data
        if(!$data){

            http_response_code(400);
            echo json_encode(["message" => "Invalid input data", "data" => $data]);
            exit();

        }

        // Initialize database connection
        $database = new Database();
        $db = $database->connect();

        // process registration
        $registration = new UserRegistration($db);
        $response = $registration->userRegistration($data);

        // output response
        echo $response->response();

    }catch(Exception $e){

        http_response_code(500);
        echo json_encode([
            "message" => "Server error",
            "error" => $e->getMessage(),
        ]);

    }



    



?>