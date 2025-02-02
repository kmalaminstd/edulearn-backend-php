<?php

    require './src/controllers/Response.php';


    class GetMyEnrollCourse{

        private $conn;

        public function __construct($db)
        {
            $this->conn = $db;
        }

        public function myCourse($data){

            try{

                $query = 'SELECT enrolled_course.enrollment_date ,enrolled_course.enrollment_id, enrolled_course.student_id , enrolled_course.payment, users.id as user_id, users.username as author_name, course.id as course_id, course.thumbnail_url, course.video_url, course.title, course.description, course.category as category FROM enrolled_course JOIN course ON enrolled_course.course_id = course.id JOIN users ON course.teacher_id = users.id  WHERE enrolled_course.student_id = :user_id';
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':user_id' , $data['user_id']);
                $stmt->execute();
                $courseList = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return new Response(200, 'Data found', $courseList);

            }catch(Exception $e){
                return new Response(500, 'Server error' , $e);
            }

            
        }
        
    }

?>