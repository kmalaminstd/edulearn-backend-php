<?php

    require './src/services/VideoUploadService.php';
    require './src/services/ImageUploadService.php';
    require './src/model/VideoModel.php';

    class VideoController {

        private $conn;
        private $videModel;

        public function __construct($db){
            $this->conn = $db;
            $this->videModel = new VideoModel($db);
        }

        public function videoUpload($data, $teacherId){

            // field validation
            if(!$data || !isset($data->title , $data->description , $data->videFile, $data->thumbnailFile)){
                return new Response(400, "Invalid field");
            }

            try{

                $uplaodService = new VideoUploadService();
                $videoUploadResult = $uplaodService->uploadVideo($data->videoFile);

                $uploadImageServ = new ImageUploadService();
                $imageUploadResult = $uploadImageServ->uploadImage($data->imageFile);
    
                if(!$videoUploadResult){
                    return new Response(400, 'Video upload failed');
                }

                

                // if(!)
    
                // video data arr
                $videoData = [
                    'title' => $data->title,
                    'description' => $data->description,
                    'video_url' => $videoUploadResult,
                    'thumbnail_url' => $imageUploadResult,
                    'teacher_id' => $teacherId
                ];

                if(!$this->videModel->create($videoData)){
                    return new Response(400, 'Failed to save data');
                }

                return new Response(201, 'Data saved successfully');
    
 
            }catch(Exception $e){
                return new Response(500 , "Server error" , ["data" => $e]);
            }


        }
    }

?>