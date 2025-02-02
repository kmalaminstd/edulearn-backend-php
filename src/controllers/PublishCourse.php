<?php

    require './src/controllers/Response.php';

    class PublishCourse {

        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function publish($data){

            
            if(!$data){
                return new Response(400, 'Data is unmodified');
            }
            // return new Response(200, $data);

            $query = 'INSERT INTO course (title , description , video_url , thumbnail_url , teacher_id , category, price) VALUES (:title, :description , :video_url , :thumbnail_url, :teacher_id, :category, :price)';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':title' , $data['title']);
            $stmt->bindParam(':description' , $data['description']);
            $stmt->bindParam(':video_url' , $data['video_link']);
            $stmt->bindParam(':thumbnail_url' , $data['image_link']);
            $stmt->bindParam(':teacher_id' , $data['user_id']);
            $stmt->bindParam(':category' , $data['category']);
            $stmt->bindParam(':price' , $data['price']);


            if($stmt->execute()){

                return new Response(200, 'Course publishes successfully');

            }else{
                return new Response(500, 'Server error');
            }

        }

    }

?>