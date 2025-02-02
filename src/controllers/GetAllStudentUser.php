<?php

    require './src/controllers/Response.php';

    class GetAllStudentUsers {

        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function studentUser(){

            try{
                
                $query = "SELECT * FROM users WHERE role_id = 1 ";
                $stmt = $this->conn->prepare($query);

                if($stmt->execute()){

                    $stdUser = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return new Response(200, 'Student user found', [
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