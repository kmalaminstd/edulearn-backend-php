<?php

    require './src/controllers/Response.php';

    class GetCategories {

        private $conn;

        
        public function __construct($db){
            $this->conn = $db;
        }

        public function getCategory() {

            try{

                $query = 'SELECT * FROM category';
                $stmt = $this->conn->prepare($query);
                $stmt->execute();

                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return new Response(200, 'Category found' , ['data' => $data]);

            }catch(Exception $e){

                return new Response(500, 'Server error');

            }
            

        }

    }

?>