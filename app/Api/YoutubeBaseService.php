<?php


namespace App\Api;

use App\Channel;
use App\Data\Repository\Impl\ChannelRepositoryImpl;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
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

    private $tokenOld;

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
        $this->tokenOld = $token;
    }

    public function setChannel($channel){
        if($channel == null)
            throw new InvalidArgumentException("Channel must not be null!");
        $this->channel = $channel;
    }

    protected function beforeService(){
        if($this->channel){
            $token = $this->client->getAccessToken();
            Log::debug(json_encode($token));
            if (isset($token['refresh_token']) && $this->client->isAccessTokenExpired()) {
                $userId = $this->channel['user_id'];
                $newToken = $this->client->refreshToken($token['refresh_token']);
                try{

                    $this->channelRepository->update($this->channel['id'], $userId, array(
                        'access_token' => $newToken['access_token'],
                        'refresh_token' => $newToken['refresh_token'],
                        'expires_in' => $newToken['expires_in'],
                        'iat' => Carbon::createFromTimestamp( $newToken['created'] + $newToken['expires_in'])
                    ));

                    $mergeToken = array_merge($this->tokenOld, array(
                        'access_token' => $newToken['access_token'],
                        'refresh_token' => $newToken['refresh_token'],
                        'expires_in' => $newToken['expires_in'],
                        'created' => $newToken['created']
                    ));
                    $this->client->getCache()->clear();
                    $this->setDeveloperToken($mergeToken);
                }catch (QueryException $e){
                    Log::error($e->getMessage(), $e->getTrace());
                }
            }
        }

    }
}