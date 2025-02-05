<?php

    require './src/controllers/Response.php';

    class AdRecentCourses{

        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function readRecCourse(){

            try{

                $query = 'SELECT 
                    course.id AS course_id, 
                    title, 
                    description, 
                    course.video_url, 
                    course.thumbnail_url, 
                    course.price, 
                    course.category, 
                    course.created_at AS publish_date, 
                    users.username AS author_name 
                FROM course 
                JOIN users ON users.id = course.teacher_id 
                ORDER BY course.created_at DESC 
                LIMIT 3';
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