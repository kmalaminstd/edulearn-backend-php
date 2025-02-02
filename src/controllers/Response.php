<?php

    class Response { 

        public $statusCode;
        public $message;
        public $data = null;

        public function __construct($statusCode, $message, $data = null){
            $this->statusCode = $statusCode;
            $this->message = $message;
            $this->data = $data;
        }

        public function response(){

            http_response_code($this->statusCode);
            return json_encode([
                "message" => $this->message,
                "data" => $this->data
            ]);

        }
    }

?>