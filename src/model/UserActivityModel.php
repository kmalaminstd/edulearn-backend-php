<?php

    class UserActivityModel{

        private $conn;

        public function __construct($db)
        {
            $this->conn = $db;
        }

        public function update($data){
            $query = 'UPDATE users SET last_activity = NOW() WHERE id = :id';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $data['id']);
            $stmt->execute();
        }

        public function read($data){
            $query = 'SELECT last_activity FROM users WHERE id = :id';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $data['id']);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

?>