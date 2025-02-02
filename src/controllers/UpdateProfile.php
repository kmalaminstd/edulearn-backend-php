<?php

    require './src/controllers/Response.php';


    class UpdateProfile {

        private $conn;
        private $getImgServ;
        
        public function __construct($db){
            $this->conn = $db;
            $this->getImgServ = new ImageUploadService();
        }

        public function updateProfile($data){

            $decoded = json_decode($data);

            try{

                $query = 'UPDATE users SET image_link = :image_link, username = :username WHERE id = :id';
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':image_link' , $decoded->image);
                $stmt->bindParam(':username' , $decoded->username);
                $stmt->bindParam(':id' , $decoded->user_id);

                if($stmt->execute()){
                    return new Response(200, $decoded->user_id);
                }else{
                    return new Response(500, 'Failed to update profile');
                }
    

            }catch(Exception $e){
                return new Response(500, 'Server error', ['error' => $e]);
            }

            
        }


    }

?>