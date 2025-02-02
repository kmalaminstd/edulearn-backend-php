<?php

    require "./src/helpers/JWThelper.php";

    class AuthMiddleware {

        public function verifyToken($token){
            try{

                $jwt = new JWThelper();
                return $jwt->tokenVerify($token);

            }catch(Exception $e){
                return json_encode([
                    'error' => $e,
                    'token' => $token,
                    'message' => 'not verified'
                ]);
            }
        }

        public function hasRole($token , $roles){

            $decoded = $this->verifyToken($token);

            // return $decoded;

            if(!$decoded) return false;

         

            if($decoded->role !== $roles) return false;

            return true;
            

        }

    }

?>