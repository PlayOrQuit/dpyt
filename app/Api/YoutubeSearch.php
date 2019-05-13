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
     * @param int $maxResult
     * @param string $regionCode
     * @param string $relevanceLanguage
     * @param string $orderBy
     * @param string $type
     * @return Google_Service_YouTube_SearchListResponse
     * @throws Google_Exception
     * @throws Google_Service_Exception
     * @throws KeyNotFoundException '
     */
    public function searchKeyWord($q, $maxResult = 20, $regionCode = 'VN', $relevanceLanguage = 'vi', $orderBy = 'viewCount', $type = 'video'){
        $this->client->checkApiKey();
        $params['q'] = $q;
        $params['maxResults'] = $maxResult;
        $params['regionCode'] = $regionCode;
        $params['relevanceLanguage'] = $relevanceLanguage;
        $params['order'] = $orderBy;
        $params['type'] = $type;
        return $this->search($params);
    }


    /**
     * @param $id
     * @param string $part
     * @return Google_Service_YouTube_VideoListResponse
     * @throws Google_Exception
     * @throws Google_Service_Exception
     * @throws KeyNotFoundException
     */
    public function searchVideoById($id, $part = 'snippet'){
        $this->client->checkApiKey();
        try{
            $params = array(
                'id' => $id
            );
            return $this->youtube->videos->listVideos($part, $params);
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