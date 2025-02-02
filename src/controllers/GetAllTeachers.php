<?php

    require './src/controllers/Response.php';

    class GetAllTeacherUsers {

        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function teacherUser(){

            try{
                
                $query = "SELECT * FROM users WHERE role_id = 2 ";
                $stmt = $this->conn->prepare($query);

                if($stmt->execute()){

                    $stdUser = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return new Response(200, 'Teacher user found', [
                        'data' => $stdUser
                    ]);
                  
                }else{
                    return new Response(400, 'Internal error');
                }

            }catch(Exception $e){
                return new Response(500, 'Server error', ['error' => $e]);
            }


            
        }
    }

?>