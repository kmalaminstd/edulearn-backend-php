<?php

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    class JWThelper {

        public function tokenGenerator($payload){

            return JWT::encode($payload , $_ENV['JWT_SECRET_KEY'] , "HS256");

        }

        public function tokenVerify($token){

            if(!$token){
                throw new Exception("Token not provided");
            }

            try{

                return JWT::decode($token, new Key($_ENV['JWT_SECRET_KEY'] , 'HS256'));

            }catch(Exception $e){
                return false;
            }

        }

    }

?>