<?php

    require "./src/controllers/Response.php";
    require "./src/helpers/JWThelper.php";

    class Login {

        // private $email;
        // private $password;

        public $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function userLogin($data){

            // $this->email = trim($data->email);
            // $this->password = trim($data->password);

            if(!$data || !isset($data->email , $data->password)){
                return new Response(400, "Invalid field" , ['email' => $data->email]);
            }

            $query = "SELECT u.id, u.username, u.email, u.password, r.name as role_name 
            FROM users u 
            INNER JOIN roles r ON u.role_id = r.id 
            WHERE u.email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email' , $data->email);
            $stmt->execute();

            if($stmt->rowCount() > 0){

                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if(password_verify($data->password , $user['password'])){

                    $jwt = new JWThelper();

                    $payload = [
                        'user_id' => $user['id'],
                        'uername' => $user['username'],
                        'email' => $user['email'],
                        'role' => $user['role_name'],
                        'exp' => time() + ( 7 * 24 * 60 * 60)
                    ];

                    $token = $jwt->tokenGenerator($payload);

                    return new Response(200 , 'Login Successfull', [
                        "token" => $token,
                        'username' => $user['username'],
                        'user_id' => $user['id'],
                        'email' => $user['email'],
                        "role" => $user['role_name']
                    ]);

                }else{
                    return new Response(401 , "User not found" , ["email" => $data -> email]);
                }
                
            }else{
                return new Response(400, "Something went wrong");
            }

        }

    }

?>