<?php

namespace App\Api;
use Google_Exception;
use Google_Service_Exception;
use Google_Service_YouTube;
use Google_Service_YouTube_SearchListResponse;
use Google_Service_YouTube_VideoListResponse;
use Illuminate\Support\Facades\Log;

class YoutubeSearch{

    private $client;
    private $youtube;

    public function __construct($apiKey)
    {
        $this->client = new YoutubeClient();
        $this->client->setApiKey($apiKey);
        $this->youtube = new Google_Service_YouTube($this->client);
    }

    /**
     * @param $q
     * @param $maxResult
     * @param $regionCode
     * @param $relevanceLanguage
     * @return Google_Service_YouTube_SearchListResponse
     * @throws KeyNotFoundException'
     * @throws Google_Service_Exception
     * @throws Google_Exception
     */
    public function searchKeyWord($q, $maxResult = 20, $regionCode = 'VN', $relevanceLanguage = 'vi'){
        $this->client->checkApiKey();
        $params['q'] = $q;
        $params['maxResults'] = $maxResult;
        $params['regionCode'] = $regionCode;
        $params['relevanceLanguage'] = $relevanceLanguage;
        return $this->search($params);
    }


    /**
     * @param $id
     * @return Google_Service_YouTube_VideoListResponse
     * @throws KeyNotFoundException
     * @throws Google_Service_Exception
     * @throws Google_Exception
     */
    public function searchVideoById($id){
        $this->client->checkApiKey();
        try{
            $params = array(
                'id' => $id
            );
            return $this->youtube->videos->listVideos('snippet,contentDetails,statistics', $params);
        }catch (Google_Service_Exception $e){
            Log::error($e->getMessage(), $e->getTrace());
            throw $e;
        }catch (Google_Exception $e){
            Log::error($e->getMessage(), $e->getTrace());
            throw $e;
        }
    }

    private function search($params){
        try{
            return $this->youtube->search->listSearch('id,snippet', $params);
        }catch (Google_Service_Exception $e){
            Log::error($e->getMessage(), $e->getTrace());
            throw $e;
        }catch (Google_Exception $e){
            Log::error($e->getMessage(), $e->getTrace());
            throw $e;
        }
    }

}