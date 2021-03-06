<?php 

class jsonPOSTDecode
{
    private $decoded;

    function __construct()
    {
        if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0){
            throw new Exception('Request method must be POST!');
        }
        
        //Make sure that the content type of the POST request has been set to application/json
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        if(strcasecmp($contentType, 'application/json') != 0){
            throw new Exception('Content type must be: application/json');
        }
        
        //Receive the RAW post data.
        $content = trim(file_get_contents("php://input"));
        
        //Attempt to decode the incoming RAW post data from JSON to array
        $this->decoded = json_decode($content, true);
        
        //If json_decode failed, the JSON is invalid.
        if(!is_array($this->decoded)){
            throw new Exception('Received content contained invalid JSON!');
        }
    }

    public function getDecodedJson()
    {
        return $this->decoded;
    }
}
