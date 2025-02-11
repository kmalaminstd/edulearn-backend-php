<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require './src/controllers/Response.php';
    require './src/config/PHPMailer/Exception.php';
    require './src/config/PHPMailer/PHPMailer.php';
    require './src/config/PHPMailer/SMTP.php';

    class PasswordReset{

        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        // confirm new password
        public function confirm_password($data){

            $token = $data['token'];
            $password = $data['password'];

            try{

                $currentTime = date('Y-m-d H:i:s');

                // check if the token exists
                $searchQuery = 'SELECT email FROM password_resets WHERE token = :token AND expires_at > :current_time';
                $searchStmt =  $this->conn->prepare($searchQuery);
                $searchStmt->bindParam(':token', $token);
                $searchStmt->bindParam('current_time', $currentTime);
                $searchStmt->execute();
                $getExistEmail = $searchStmt->fetch(PDO::FETCH_ASSOC);

                if(!$getExistEmail){
                    // delete the token if expires
                    $dltTokenStmt = $this->conn->prepare('DELETE FROM password_resets WHERE expires_at < NOW()');
                    $dltTokenStmt->execute();
                    return new Response(404, 'Token not found', $token);
                }

                // update password in users
                $passwordHashed = password_hash($password, PASSWORD_BCRYPT);
                $updateQuery = 'UPDATE users SET password = :password WHERE email = :email';
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(':password', $passwordHashed);
                $updateStmt->bindParam(':email', $getExistEmail['email']);
                
                if($updateStmt->execute()){

                    // delete the token in resets password
                    $deleteQuery = 'DELETE FROM password_resets WHERE email = :email';
                    $dltStmt = $this->conn->prepare($deleteQuery);
                    $dltStmt->bindParam(':email', $getExistEmail['email']);
    
                    $dltStmt->execute();
    
                    return new Response(200, 'Password update successfull');
                }else{
                    return new Response(500, 'Server error');
                }


            }catch(Exception $e){
                return new Response(400, 'Server error', $e);
            }

        }

        // reset request from user
        public function reset_request($data){

            if(!$this->search_user($data['email'])){
                return new Response(404, 'User does not exist');
            }

            // data to insert
            $token = bin2hex(random_bytes(50));
            // generate a unique reset token
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $query = 'INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':expires_at', $expires_at);


            if($stmt->execute()){

                $this->send_reset_link($data['email'], $token);

                // send reset link to email
                return new Response(200, 'Reset link sent to your email');
                
            }else{
                return new Response(500, 'Internal error');
            }



        }

        // send reset email to user
        private function send_reset_link($email, $token){

            $reset_link = "http://localhost/eduwebclientui/reset-password.php?token=$token";
            $subject = "Password Reset Request";
            $message = "Click the link below to reset your password:\n\n$reset_link";

            $mail = new PHPMailer(true);

            try{

                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'alaminkhanstd@gmail.com';
                $mail->Password = 'zggi nurq deib qlwg';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('alaminkhanstd@gmail.com', 'EduLearn');
                $mail->addAddress($email);
                $mail->Subject = $subject;
                $mail->Body = $message;

                $mail->send();

                return true;

            }catch(Exception $e){
                return false;
            }

        }

        // verify the exists user
        private function search_user($email){

            try{

                $query = 'SELECT id FROM users WHERE email = :email';
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if(!$user){
                    return false;
                }else{
                    return true;
                }

            }catch(Exception $e){
                return false;
            }
        }
    }

?>