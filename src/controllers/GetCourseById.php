<?php

    require './src/controllers/Response.php';

    class GetCourseById{

        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function getCourse($id){
            try{

                $query = 'SELECT 
                course.id AS course_id, course.title, course.description, course.thumbnail_url, course.price, course.created_at AS course_published, course.updated_at AS course_update, users.username AS author 
                FROM course
                JOIN users ON course.teacher_id = users.id WHERE course.id = :id';
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $courseDetails = $stmt->fetch(PDO::FETCH_ASSOC);

                // Debugging: Print the ID and check if courseDetails is empty
                if (!$courseDetails) {
                    return new Response(404, "Course not found. ID: $id", false);
                }

                return new Response(200, 'Course found', $courseDetails);

            }catch(Exception $e){
                return new Response(500, 'Server Error', $e);
            }
        }
    }

?>