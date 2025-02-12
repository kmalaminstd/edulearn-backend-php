<?php

    require './src/controllers/Response.php';

    class AdCourseList{

        private $conn;
        private $limit = 10;
        private $offset;

        public function __construct($db, $page){
            $this->conn = $db;
            $this->offset = ($page - 1) * $this->limit;
        }

        public function readList(){

            try{

                $query = "SELECT 
                course.id as course_id, title, description, course.video_url, course.thumbnail_url, course.price, course.category, course.created_at as publish_date, users.username as author_name,
                COUNT(course_id) as number_of_student
                FROM course 
                JOIN users ON users.id = course.teacher_id
                LEFT JOIN enrolled_course ON course.id = enrolled_course.course_id
                GROUP BY course.id, users.username
                LIMIT $this->limit 
                OFFSET $this->offset";

                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return new Response(200, 'Data found', $data);

            }catch(Exception $e){
                return new Response(500, 'Server error', $e);
            }
        }

    }

?>