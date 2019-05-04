<?php


namespace App\Api;

use Google_Client;
use InvalidArgumentException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class YoutubeClient extends Google_Client{

    private $channel;
    private $developerKey;

    public function setChannel($channel){
        if($channel == null)
            throw new InvalidArgumentException("Channel must not be null!");
        $this->channel = $channel;
        $token = array(
            'access_token' => $channel->access_token,
            'refresh_token' => $channel->refresh_token
        );
        $this->setAccessToken($token);
        
    }
    
    public function isAccessTokenExpired()
    {
        if($this->channel->iat){
            if($this->channel->iat > now()){
                Log::debug("isAccessTokenExpired false");
                return false;
            }
        }
        Log::debug("isAccessTokenExpired true");
        return true;
    }

    private function updateChannel($accessToken, $refreshToken, $iat){
        $this->channel->access_token = $accessToken;
        $this->channel->refresh_token = $refreshToken;
        $this->channel->iat = $iat;
        $this->channel->save()->refresh();
    }

    /**
     * @throws AuthenticationException
     */
    public function checkAuth(){
        if(!$this->getAccessToken()){
            throw new AuthenticationException("Not Authentication Google Exception");
        }else if($this->isAccessTokenExpired()){
            $auths = $this->refreshToken($this->channel->refresh_token);
            if ($auths && isset($auths['access_token'])) {
                $second = $auths['created'] + $this->channel->expires_in;
                $iat =  Carbon::createFromTimestamp($second);
                try{
                    $this->updateChannel($auths['access_token'], $auths['refresh_token'], $iat);
                }catch(QueryException $e){
                    Log::error($e->getMessage(), $e->getTrace());
                    throw new AuthenticationException("Not Authentication Google Exception");
                }
            }else{
                throw new AuthenticationException("Not Authentication Google Exception");
            }
        }
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