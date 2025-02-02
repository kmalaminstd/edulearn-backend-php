<?php

    require './src/controllers/Response.php';

    class GetMyCourseList {
        
        private $conn;


        public function __construct($db){
            $this->conn = $db;
        }

        public function getList($id){

            try{

                $query = 'SELECT * FROM  course WHERE teacher_id = :id';
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id' , $id);
                $stmt->execute();
                $courseData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return new Response(200, 'Data found' , ['data' => $courseData]);

            }catch(Exception $e){
                return new Response(500, 'Server error');
            }

        }
    }

?>