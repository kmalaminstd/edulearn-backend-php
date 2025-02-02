<?php

    require './src/controllers/Response.php';

    class GetUserInfo {

        private $conn;

        public function __construct($db)
        {
            $this->conn = $db;
        }

        public function getInfo($data){

            try{

                $query = 'SELECT u.* , r.name as roleName FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = :id';
                $stmt =  $this->conn->prepare($query);
                $stmt->bindParam(':id' , $data['id']);
                $stmt->execute();
                
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
                return new Response(200, 'User found' ,[
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['roleName'],
                    'email' => $user['email'],
                    'role_id' => $user['role_id'],
                    'image_link' => $user['image_link']
                ]);

            }catch(Exception $e){

                return new Response(500, 'Server error');

            }
            

        }

    }

?>