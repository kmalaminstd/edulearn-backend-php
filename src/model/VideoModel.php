<?php

    class VideoModel {

        private $conn;
        

        public function __construct($db)
        {
            $this->conn = $db;
        }

        public function create($data){

            $query = 'INSERT INTO videos (title , description , video_url, thumbnail, teacher_id) VALUES (:title, :description, :video_url, :thumbnail, :teacher_id)';

            $stmt = $this->conn->prepare($query);
            
            return $stmt->execute($data);

        }

    }

?>