<?php

    require './src/controllers/Response.php';

    class EnrollCourse{

        private $conn;

        public function __construct($db) 
        {   
            $this->conn = $db;
        }

        public function enroll($data){

            if(!isset($data)){
               return new Response(400, 'Invalid data'); 
            }


            try{

                $checkQuery = 'SELECT COUNT(*) as count FROM enrolled_course WHERE course_id = :course_id AND student_id = :student_id';
                $checkStmt = $this->conn->prepare($checkQuery);
                $checkStmt->bindParam(':course_id', $data['course_id']);
                $checkStmt->bindParam(':student_id', $data['student_id']);
                $checkStmt->execute();
                $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
                    
                if($result['count'] > 0){
                    return new Response(209, 'Already enrolled the courses');
                    exit;
                }

                
                $query = 'INSERT INTO enrolled_course (student_id, course_id, payment) VALUES (:student_id, :course_id, :payment)';
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':student_id' , $data['student_id']);
                $stmt->bindParam(':course_id' , $data['course_id']);
                $stmt->bindParam(':payment' , $data['payment']);

                if($stmt->execute()){

                    return new Response(200, 'Successfully enrolled course');
                }else{
                    return new Response(501, 'Bad execution');
                }


            }catch(Exception $e){
                return new Response(500, 'Server error', $e);
            }

        }
    }

?>