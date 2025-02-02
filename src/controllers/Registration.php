<?php

    require "./src/controllers/Response.php";
    require './src/helpers/JWThelper.php';

    class UserRegistration {

        private $conn;
        private $username;
        private $email;
        private $password;
        private $role_id;
        // private $image_link;

        public function __construct($db){ 
            $this->conn = $db;
        }


        public function userRegistration($data){


            $this->username = trim($data->username);
            $this->email = trim($data->email);
            $this->role_id = trim($data->role_id);
            // $this->image_link = trim($data->image_link);
            $this->password = trim($data->password);

            // field validation
            if(!isset($this->username , $this->email , $this->password , $this->role_id)){

                return new Response(400 , "Invalid field", ['data' => $data]);

            }

            // checking existing user
            $existUserSqlQuery = "SELECT COUNT(*) FROM users WHERE email = :email";
            $existUserStmt = $this->conn->prepare($existUserSqlQuery);
            $existUserStmt->execute(["email" => $this->email]);


            if($existUserStmt->fetchColumn() > 0){

                return new Response(400, "User already exist");

            }

            // Insert new user
            $newUserQuery = "INSERT INTO users (username , email , role_id , password) VALUES (:username , :email , :role_id , :password)";
            $newUserStmt = $this->conn->prepare($newUserQuery);

            if($newUserStmt->execute(["username" => $this->username,"email" => $this->email,"role_id" => $this->role_id,"password" => password_hash($this->password , PASSWORD_BCRYPT)])){

                return new Response(201, "User registered success", ["data" => "created"]);

            }else{
                return new Response(400 , "Failed to create user");
            }

        }

    }

?>