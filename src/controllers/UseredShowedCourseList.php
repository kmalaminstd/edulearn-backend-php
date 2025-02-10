<?php


    require "./src/controllers/Response.php";

    class UseredShowedCourseList{

        private $conn;
        private $limit = 10;
        private $offset;

        public function __construct($db, $page_number){
            $this->conn = $db;
            $this->offset = ($page_number - 1) * $this->limit;
        }

        public function courseList(){

            

            try{

                $query = 'SELECT * , course.created_at as publish_time , course.id as course_id, (SELECT COUNT(*) FROM course) AS total_course FROM course JOIN users ON course.teacher_id = users.id LIMIT :limit OFFSET :offset';
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':limit' , $this->limit , PDO::PARAM_INT);
                $stmt->bindParam(':offset' , $this->offset, PDO::PARAM_INT);
                $stmt->execute();
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return new Response(200, 'Data found' , $data);

            }catch(Exception $e){

                return new Response(500, 'Server error', $e);

            }

        }
        
    }


?>