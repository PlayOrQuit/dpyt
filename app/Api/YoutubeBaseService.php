<?php


namespace App\Api;

use InvalidArgumentException;
use Google_Service_YouTube;

class YoutubeBaseService{
    
    protected $client;
    protected $youtube;

    public function __construct()
    {
        $this->client = new YoutubeClient();
        $this->youtube = new Google_Service_YouTube($this->client);
    }

    public function setDeveloperToken($token){
        if($token == null)
            throw new InvalidArgumentException("Token must not be null!");
        $this->client->setDeveloperToken($token);
    }
}