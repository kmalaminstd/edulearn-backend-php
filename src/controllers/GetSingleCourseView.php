<?php

    require './src/controllers/Response.php';

    class GetSingleCourseView {

        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function getCourseView($id, $teacher_id){

            try{

                $query = 'SELECT * FROM course WHERE id = :id AND teacher_id = :teacher_id';
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id' , $id);
                $stmt->bindParam(':teacher_id' , $teacher_id);
                $stmt->execute();
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return new Response(200, 'Data found' , $data);

            }catch(Exception $e){
                return new Response(500, 'Server error');
            }
        }

    }

?>