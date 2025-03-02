<?php

    require './src/controllers/Response.php';

    class isEnrolled {

        public $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function verifyEnroll($data){

            try{

                $query = 'SELECT COUNT(*) FROM enrolled_course WHERE student_id = :std_id AND course_id = :crs_id';
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':std_id', $data['student_id']);
                $stmt->bindParam(':crs_id', $data['course_id']);
                $numOfRow = $stmt->execute();
    
                if($numOfRow > 0){
                    return new Response(200, 'Data found', true);
                }else{
                    return new Response(404, 'Data not found', false);
                }

            }catch(Exception $e){
                return new Response(500, 'Server error', $e);
            }

        }

    }

?>