<?php

    require './src/controllers/Response.php';

    class RecentUser {

        public $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function recentUserList(){

            try{

                $query = "SELECT u.*, r.name as role_name ,
                        CASE 
                        WHEN u.last_activity >= NOW() - INTERVAL 5 MINUTE THEN 'Online' ELSE 'Offline'
                        END AS isOnline
                         FROM users u 
                         JOIN roles r ON u.role_id = r.id
                         ORDER BY created_at DESC LIMIT 2";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
    
    
                if($stmt->rowCount() === 0){
    
                    return new Response(404, 'No users found');
                    
                }
    
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                // echo $users;
    
                return new Response(200, 'User found', ['user' => $users]);
            }catch(Exception $e){
                return new Response(500, 'Server Error' , ['error' => $e]);
            }


        }
    }

?>