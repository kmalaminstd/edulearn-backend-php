<?php

    class Database {

        private $DB_HOST = 'localhost';
        private $DB_NAME = 'eduwebbackend';
        private $DB_USER = 'root';
        private $DB_PASS = '';

        public $conn;

        public function connect(){

            $dsn = "mysql:host=$this->DB_HOST;dbname=$this->DB_NAME";

            try{

                $this->conn = new PDO($dsn , $this->DB_USER , $this->DB_PASS);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION );
                // echo "Success";

            }catch(Exception $e){
                echo $e;
            }

            return $this->conn;
        }
    }

?>