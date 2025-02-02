<?php


    require "./src/controllers/Response.php";

    class UseredShowedCourseList{

        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function courseList(){

            try{

                $query = 'SELECT * , course.created_at as publish_time , course.id as course_id FROM course JOIN users ON course.teacher_id = users.id';
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return new Response(200, 'Data found' , $data);

            }catch(Exception $e){

                return new Response(500, 'Server error', $e);

            }

        }
        
    }


?>