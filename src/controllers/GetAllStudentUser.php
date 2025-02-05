<?php

    require './src/controllers/Response.php';

    class GetAllStudentUsers {

        private $conn;
        private $page;
        private $limit = 10;

        public function __construct($db , $page){
            $this->conn = $db;
            $this->page = $page;
        }

        public function studentUser(){

            try{

                $offset = ($this->page - 1) * $this->limit;
                
                $query = "SELECT * FROM users WHERE role_id = 1 LIMIT $this->limit OFFSET  $offset";
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