<?php


namespace App\Api;

use App\Data\Repository\Impl\ChannelRepositoryImpl;
use Google_Client;
use InvalidArgumentException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

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
    
//    public function isAccessTokenExpired()
////    {
////        if($this->channel->iat){
////            if($this->channel->iat > now()){
////                Log::debug("isAccessTokenExpired false");
////                return false;
////            }
////        }
////        Log::debug("isAccessTokenExpired true");
////        return true;
////    }

//    private function updateChannel($accessToken, $refreshToken, $iat){
//        $userId = \Auth::user()->id;
//        $result = $this->channelRepository->update($this->channel['id'], $userId, array(
//            'access_token' => $accessToken,
//            'refresh_token' => $refreshToken,
//            'iat' => $iat,
//        ));
//        if($result){
//            $this->setChannel($this->channelRepository->findById($this->channel['id'], $userId, array(
//                'id',
//                'access_token',
//                'refresh_token',
//                'token_type',
//                'expires_in',
//                'iat'
//            )));
//        }
//
//    }

    /**
     * @throws AuthenticationException
     */
//    public function checkAuth(){
//        if(!$this->getAccessToken()){
//            throw new AuthenticationException("Not Authentication Google Exception");
//        }else if($this->isAccessTokenExpired() == true){
//            $auths = $this->refreshToken($this->channel['refresh_token']);
//            if ($auths && isset($auths['access_token'])) {
//                $second = $auths['created'] + $this->channel['expires_in'];
//                $iat =  Carbon::createFromTimestamp($second);
//                try{
//                    $this->updateChannel($auths['access_token'], $auths['refresh_token'], $iat);
//                }catch(QueryException $e){
//                    Log::error($e->getMessage(), $e->getTrace());
//                    throw new AuthenticationException("Not Authentication Google Exception");
//                }
//            }else{
//                throw new AuthenticationException("Not Authentication Google Exception");
//            }
//        }
//    }

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