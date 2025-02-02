<?php

    require './src/controllers/RecentUser.php';

    class StatCounter {

        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function count(){

            try{

                $userCountQuery = 'SELECT COUNT(*) AS total_users FROM users';
                $teacherCountQuery = 'SELECT COUNT(*) AS total_teachers FROM users WHERE role_id = 2';
                $studentCountQuery = 'SELECT COUNT(*) AS total_student FROM users WHERE role_id = 1';
                $courseCountQuery = 'SELECT COUNT(*) AS total_courses FROM videos';
    
                $uStmt = $this->conn->prepare($userCountQuery);
                $uStmt->execute();
                $totalUser = $uStmt->fetchColumn();

                $tStmt = $this->conn->prepare($teacherCountQuery);
                $tStmt->execute();
                $totalTeacher = $tStmt->fetchColumn();

                $sStmt = $this->conn->prepare($studentCountQuery);
                $sStmt->execute();
                $totalStudent = $sStmt->fetchColumn();

                $cStmt = $this->conn->prepare($courseCountQuery);
                $cStmt->execute();
                $totalCourse = $cStmt->fetchColumn();

                return new Response(200, 'Stat is ready' , [
                    "total_user" => $totalUser,
                    'total_teacher' => $totalTeacher,
                    'total_student' => $totalStudent,
                    'total_course' => $totalCourse
                ]);

            }catch(Exception $e){
                return new Response(500, 'Server error', ['error' => $e]);
            }


            

        }
    }

?>