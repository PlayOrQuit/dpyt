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

    public function setChannel($channel){
        if($channel == null)
            throw new InvalidArgumentException("Channel must not be null!");
        $this->client->setChannel($channel);
    }
}