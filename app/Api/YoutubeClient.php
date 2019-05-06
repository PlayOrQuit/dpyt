<?php


namespace App\Api;


use Google_Client;
use InvalidArgumentException;

class YoutubeClient extends Google_Client{

    private $developerKey;

    public function setDeveloperToken($token){
        if($token == null)
            throw new InvalidArgumentException("Token must not be null!");
        $this->setAccessToken($token);
        if(isset($token['client_id']))
            $this->setClientId($token['client_id']);
        if(isset($token['client_secret']))
            $this->setClientSecret($token['client_secret']);
    }


    public function setApiKey($apiKey)
    {
        $this->developerKey = $apiKey;
        $this->setDeveloperKey($apiKey);
    }

    /**
     * @throws KeyNotFoundException
     */
    public function checkApiKey(){
        if(!$this->developerKey){
            throw new KeyNotFoundException("ApiKey Not Found Exception");
        }
    }

}