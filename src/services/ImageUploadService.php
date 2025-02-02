<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class ImageUploadService {
    private $cloudinary;
    private $uploadApi;

    public function __construct() {
        // Validate required environment variables
        if (!isset($_ENV['CLOUDINARY_CLOUD_NAME']) || 
            !isset($_ENV['CLOUDINARY_API_KEY']) || 
            !isset($_ENV['CLOUDINARY_API_SECRET'])) {
            throw new Exception('Cloudinary credentials not set in environment');
        }

        // Configure Cloudinary
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
                'api_key' => $_ENV['CLOUDINARY_API_KEY'],
                'api_secret' => $_ENV['CLOUDINARY_API_SECRET']
            ]
        ]);
        
        $this->uploadApi = new UploadApi($this->cloudinary->configuration);
    }

    public function uploadImage($file) {
        try {
            if (!isset($file['tmp_name'])) {
                throw new Exception('No file uploaded');
            }

            $result = $this->uploadApi->upload($file['tmp_name'], [
                'folder' => 'eduweb_profiles',
                'resource_type' => 'image'
            ]);

            return [
                'success' => true,
                'url' => $result['secure_url']
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}