<?php

    require "./src/controllers/Response.php";
    require './src/model/UserActivityModel.php';

    class UserActivity{

        private $db;

        public function __construct($db){
            $this->db = $db;
        }

        public function setLastActivity($data){
            try{
                $useAct = new UserActivityModel($this->db);
                $useAct->update($data);
                return new Response(200, 'Update successfully');
            }catch(Exception $e){
                return new Response(500, 'Server error', $e);
            }
        }

        // public function readLastActivity($data){
        //     try{
        //         $useAct = new UserActivityModel($this->db);
        //         $last_activity_time = $useAct->read($data);
        //         return new Response(200, 'Found data', $last_activity_time);
        //     }catch(Exception $e){
        //         return new Response(500, 'Server error', $e);
        //     }
        // }


    }


?>