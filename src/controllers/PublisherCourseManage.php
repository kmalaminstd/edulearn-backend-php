<?php

    require "./src/controllers/Response.php";

    class PublisherCourseManage{

        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function deleteCourse($data){

            try{

                $query = 'DELETE FROM course WHERE id = :id';
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $data['id']);
                $stmt->execute();
                
                return new Response(200, 'Data deleted successfully');

            }catch(Exception $e){
                return new Response(500, 'Server error', $e);
            }
        }

        public function updateCourse($data){
            
            try{

                $query = 'UPDATE course SET title = :title, description = :description , category= :category, price = :price, updated_at = :updated_at WHERE id = :id';
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $data['id']);
                $stmt->bindParam(':title', $data['title']);
                $stmt->bindParam(':description', $data['description']);
                $stmt->bindParam(':category', $data['category']);
                $stmt->bindParam(':price', $data['price']);
                $stmt->bindParam(':updated_at', $data['updated_at']);

                $stmt->execute();

                return new Response(200, 'Course updated successfully');
                

            }catch(Exception $e){
                return new Response(500, 'Server error', $e);
            }
        }
    }

?>