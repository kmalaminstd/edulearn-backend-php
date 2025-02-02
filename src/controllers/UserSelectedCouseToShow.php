<?php

    require './src/controllers/Response.php';

    class UserSelectedCourseToShow{

        private $conn;
        public $details;

        public function __construct($db){
            $this->conn = $db;
        }

        public function selectedCourse($data){
            
            try{

                

                
                        
                        $query = 'SELECT course.id as course_id, users.username as author, course.thumbnail_url, course.video_url, course.description, course.title FROM course JOIN enrolled_course ON course.id = enrolled_course.course_id JOIN users ON users.id = course.teacher_id WHERE course.id = :course_id AND enrolled_course.student_id = :student_id';
                        $stmt = $this->conn->prepare($query);
                        $stmt->bindParam(':course_id', $data->course_id);
                        $stmt->bindParam(':student_id', $data->user_id);
                        if($stmt->execute()){
                            $this->details = $stmt->fetch(PDO::FETCH_ASSOC);
                        }

                        return new Response(200,'success', $this->details);

                   


            }catch(Exception $e){
                return new Response(500, 'Server error', $e);
            }
        }
    }

?>