<?php

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    require './src/config/Database.php';
    require './src/controllers/PublishCourse.php';
    require './src/services/ImageUploadService.php';
    require './src/services/VideoUploadService.php';
    require './src/middlewares/AuthMiddleware.php';

    $headers = getallheaders();

    if(!$headers['Authorization']){
        http_response_code(400);
        echo json_encode(['message' => 'Header is missing']);
        exit;
    }

    $token = trim(str_replace('Bearer' , '' ,$headers['Authorization']));
    $user_id = $headers['user_id'];

    // verify method
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        http_response_code(403);
        echo json_encode(['message' => 'Method not allowed']);
        exit;
    }

    $database = new Database();
    $db = $database->connect();


    $title = filter_input(INPUT_POST, 'title' , FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description' , FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category = filter_input(INPUT_POST, 'category' , FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price' , FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $image = $_FILES['image'];
    $video = $_FILES['video'];

    // video upload process and get the link
    $videoCls = new VideoUploadService();
    $videoUpRes = $videoCls->uploadVideo($video);
    $videoUrl = isset($videoUpRes['url']) ? $videoUpRes['url'] : null;
    $videoErr = isset($videoUpRes['error']) ? $videoUpRes['error'] : null;
    // $videoErr = $videoUpRes['error'];

    if($videoErr){
        http_response_code(500);
        echo json_encode(['message' => $videoErr]);
        exit;
    }

    if(!$videoUrl){
        http_response_code(400);
        echo json_encode(['message' => 'Video upload faild']);
        exit;
    }

    // image upload process and get the link
    $imageCls = new ImageUploadService();
    $imageUpRes = $imageCls->uploadImage($image);
    $imageUrl = isset($imageUpRes['url']) ? $imageUpRes['url'] : null;

    $publishCourseCls = new PublishCourse($db);
    $resp = $publishCourseCls->publish([
        'title' => $title,
        'description' => $description,
        'category' => $category,
        'price' => $price,
        'image_link' => $imageUrl,
        'video_link' => $videoUrl,
        'user_id' => $user_id
    ]);

    echo $resp->response();

?>