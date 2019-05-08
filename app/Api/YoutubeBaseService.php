<?php


namespace App\Api;

use App\Channel;
use App\Data\Repository\Impl\ChannelRepositoryImpl;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Google_Service_YouTube;

class YoutubeBaseService{
    /**
     * @var YoutubeClient
     */
    protected $client;
    /**
     * @var Google_Service_YouTube
     */
    protected $youtube;
    /**
     * @var Channel
     */
    private $channel;

    private $channelRepository;

    public function __construct()
    {
        $this->client = new YoutubeClient();
        $this->youtube = new Google_Service_YouTube($this->client);
        $this->channelRepository = new ChannelRepositoryImpl();
    }

    public function setDeveloperToken($token){
        if($token == null)
            throw new InvalidArgumentException("Token must not be null!");
        $this->client->setDeveloperToken($token);
    }

    public function setChannel($channel){
        if($channel == null)
            throw new InvalidArgumentException("Channel must not be null!");
        $this->channel = $channel;
    }

    protected function afterService(){
        Log::debug('call After Service');
        if($this->channel){
            Log::debug('call After Service Channel');
            $token = $this->client->getAccessToken();
            Log::debug('before => '. $this->channel['access_token']);
            Log::debug('after => '. $token['access_token']);
            Log::debug('created => '. $token['created']);
            Log::debug(json_encode($this->client->getCache()));
            if($token && strcmp($token['access_token'], $this->channel['access_token']) !== 0){
                Log::debug('call After Service access_token');
                $userId = \Auth::user()->id;
                $this->channelRepository->update($this->channel['id'], $userId, array(
                    'access_token' => $token['access_token'],
                    'refresh_token' => $token['refresh_token'],
                    'expires_in' => $token['expires_in'],
                    'iat' => Carbon::createFromTimestamp($token['created'] + $token['expires_in'])
                ));
            }
        }

    }
}