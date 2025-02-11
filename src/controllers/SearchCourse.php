<?php

    require './src/controllers/Response.php';

    class SearchCourse{

        private $conn;
        private $page;
        private $limit = 10;

        public function __construct($db, $page){
            $this->conn = $db;
            $this->page = $page;
        }

        // search course by title
        public function searchByTitle($data){

            $offset = ($this->page - 1) * $this->limit;

            try{

                $searchText = "%" . $data['searchText'] . "%";
                
                $query = 'SELECT course.title as course_title, course.id as course_id, course.description as course_description, course.thumbnail_url, course.price, course.category, course.created_at as course_publish_date, users.username as author_name FROM course 
                JOIN users ON course.teacher_id = users.id
                WHERE title LIKE :text 
                LIMIT :limit 
                OFFSET :offset';
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':text', $searchText, PDO::PARAM_STR);
                $stmt->bindValue(':limit', $this->limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return new Response(200, 'Data found', $result);
                

            }catch(Exception $e){
                return new Response(500, 'Server error', $e);
            }
        }
    }

?>