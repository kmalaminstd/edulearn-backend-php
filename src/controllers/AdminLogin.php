<?php

    require './src/helpers/JWThelper.php';
    require './src/controllers/Response.php';

    class AdminLogin {

        private $conn;

        public function __construct($db)
        {
            $this->conn = $db;
        }

        public function adminLogin($data){

            // verify data
            if(!isset($data->email , $data->password)){
                return new Response(400, "Invalid field");
            }

            // 
            $query = 'SELECT * FROM admin WHERE email = :email';
            $stmt = $this->conn->prepare($query);
            $stmt->execute(['email' => $data->email]);
            

            if($stmt->rowCount() > 0){

                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if(password_verify($data->password, $user['password'])){

                    $payload = [
                        'admin_id' => $user['id'],
                        'email' => $user['email'],
                        'role' => 'admin',
                        'exp' => time() + ( 7 * 24 * 60 * 60),
                    ];

                    $tokenGen = new JWThelper();
                    $token = $tokenGen->tokenGenerator($payload);

                    return new Response(200, "Admin login successfull", [
                        'token' => $token,
                        'user_id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'role' => 'admin',
                    ]);

                }else{
                    return new Response(400, "Passwor or email incorrect");
                }

            }else{
                return new Response(404, "Passwor or email incorrect");
            }

        }
    }

?>