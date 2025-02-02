<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST,GET");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require './src/config/Database.php';
    require './src/controllers/UpdateProfile.php';
    require './src/services/ImageUploadService.php';
    require './src/middlewares/AuthMiddleware.php';

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        http_response_code(403);
        echo json_encode(['message' => 'Method not allowed']);
        exit;
    }

    if(json_last_error() !== JSON_ERROR_NONE){
        http_response_code(400);
        echo json_encode(['message' => 'Wrong data format']);
        exit;
    }

    $database = new Database();
    $db = $database->connect();

    $headers = getallheaders();
    if(!isset($headers['Authorization']) || !isset($headers['user_id'])){
        http_response_code(400);
        echo json_encode(['message' => 'Header is missing']);
        exit;
    }

    $token = trim(str_replace('Bearer', '' , $headers['Authorization']));

    $data = json_decode(file_get_contents('php://input'), true);

    // getting the image link
    $imgFileEncoded = $data['image'];
    $imgUpdSer = new ImageUploadService();
    $link = $imgUpdSer->uploadImage($imgFileEncoded);

    // echo json_encode(['data' => $headers['user_id']]);

    // update process
    $updateProfile = new UpdateProfile($db);
    $resp = $updateProfile->updateProfile(json_encode([ 'user_id' => $headers['user_id'] ,'username' => $data['username'], 'image' => $link['url']]));
    echo $resp->response();

?>