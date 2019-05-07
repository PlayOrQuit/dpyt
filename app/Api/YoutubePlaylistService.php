<?php


namespace App\Api;


use Google_Exception;
use Google_Service_Exception;
use Google_Service_YouTube_Playlist;
use Google_Service_YouTube_PlaylistSnippet;
use Google_Service_YouTube_PlaylistStatus;
use Google_Service_YouTube_ResourceId;
use Google_Service_YouTube_PlaylistItemSnippet;
use Google_Service_YouTube_PlaylistItem;
use Illuminate\Support\Facades\Log;

class YoutubePlaylistService extends YoutubeBaseService
{

    /**
     * @param $title
     * @param null $description
     * @param null $tags
     * @throws AuthenticationException
     * @throws Google_Service_Exception
     * @throws Google_Exception
     * @return Google_Service_YouTube_Playlist
     */
    public function createPlayList($title, $hl, $description = null, $tags = null){
        $this->client->checkAuth();
        try{
            $youTubePlaylist = new Google_Service_YouTube_Playlist();
            $youTubePlaylist->setSnippet($this->createPlaylistSnippet($title, $hl, $description, $tags, ));
            $youTubePlaylist->setStatus($this->createPlaylistStatus());
            $playlistResponse = $this->youtube->playlists->insert('snippet,status', $youTubePlaylist);
            return $playlistResponse;
        }catch (Google_Service_Exception $e){
            Log::error($e->getMessage(), $e->getTrace());
            throw $e;
        }catch (Google_Exception $e){
            Log::error($e->getMessage(), $e->getTrace());
            throw $e;
        }
    }
    /**
     * @param $playlistId
     * @param $videoId
     * @param $postion
     * @param $title
     * @throws AuthenticationException
     * @throws Google_Service_Exception
     * @throws Google_Exception
     * @return Google_Service_YouTube_PlaylistItem
     */
    public function createPlaylistItem($playlistId, $videoId, $postion = null, $title = null){
        $this->client->checkAuth();
        try{
            $resourceId = $this->createResourceId(array( 'videoId' => $videoId));
            $playlistItemSnippet = $this->createPlaylistItemSnippet($playlistId, $resourceId, $postion, $title);
            $playlistItem = new Google_Service_YouTube_PlaylistItem();
            $playlistItem->setSnippet($playlistItemSnippet);
            return $this->youtube->playlistItems->insert('snippet', $playlistItem);
        }catch (Google_Service_Exception $e){
            Log::error($e->getMessage(), $e->getTrace());
            throw $e;
        }catch (Google_Exception $e){
            Log::error($e->getMessage(), $e->getTrace());
            throw $e;
        }
    }

    /**
     * @param $id
     * @param $playlistId
     * @param $videoId
     * @param $position
     * @param $title
     * @throws AuthenticationException
     * @throws Google_Service_Exception
     * @throws Google_Exception
     * @return Google_Service_YouTube_PlaylistItem
     */
    public function updatePlaylistItem($id, $playlistId, $videoId, $position = null, $title = null){
        $this->client->checkAuth();
        try{
            $resourceId = $this->createResourceId(array( 'videoId' => $videoId));
            $playlistItemSnippet = $this->createPlaylistItemSnippet($playlistId, $resourceId, $position, $title);
            $playlistItem = new Google_Service_YouTube_PlaylistItem();
            $playlistItem->setSnippet($playlistItemSnippet);
            $playlistItem->setId($id);
            return $this->youtube->playlistItems->update('snippet', $playlistItem);
        }catch (Google_Service_Exception $e){
            Log::error($e->getMessage(), $e->getTrace());
            throw $e;
        }catch (Google_Exception $e){
            Log::error($e->getMessage(), $e->getTrace());
            throw $e;
        }
    }
    /**
     * @param $id
     * @throws AuthenticationException
     * @throws Google_Service_Exception
     * @throws Google_Exception
     */
    public function deletePlaylistItem($id){
        $this->client->checkAuth();
        try{
            $playlistItem = new Google_Service_YouTube_PlaylistItem();
            $playlistItem->setId($id);
            $this->youtube->playlistItems->delete($id);
        }catch (Google_Service_Exception $e){
            Log::error($e->getMessage(), $e->getTrace());
            throw $e;
        }catch (Google_Exception $e){
            Log::error($e->getMessage(), $e->getTrace());
            throw $e;
        }
    }


    private function createPlaylistSnippet($title, $hl, $description, $tags){
        $playlistSnippet = new Google_Service_YouTube_PlaylistSnippet();
        $playlistSnippet->setTitle($title);
        $playlistSnippet->setDefaultLanguage($hl);
        if(is_string($description))
            $playlistSnippet->setDescription($description);
        if(is_array($tags))
            $playlistSnippet->setTags($tags);
        return $playlistSnippet;
    }

    private function createPlaylistStatus(){
        $playlistStatus = new Google_Service_YouTube_PlaylistStatus();
        $playlistStatus->setPrivacyStatus('public');
        return $playlistStatus;
    }

    private function createResourceId($params = array()){
        $resourceId = new Google_Service_YouTube_ResourceId();
        if(isset($params['videoId'])){
            $resourceId->setVideoId($params['videoId']);
            $resourceId->setKind('youtube#video');
        }else if(isset($params['channelId'])){
            $resourceId->setChannelId($params['channelId']);
            $resourceId->setKind('youtube#channel');   
        }else if(isset($params['playlistId'])){
            $resourceId->setPlaylistId($params['playlistId']);
            $resourceId->setKind('youtube#playlist');   
        }
        return $resourceId;
    }

    private function createPlaylistItemSnippet($playlistId, $resourceId, $postion, $title){
        $playlistItemSnippet = new Google_Service_YouTube_PlaylistItemSnippet();
        $playlistItemSnippet->setPlaylistId($playlistId);
        $playlistItemSnippet->setResourceId($resourceId);
        if(is_int($postion))
            $playlistItemSnippet->setPosition($postion);
        if(is_string($title))
            $playlistItemSnippet->setTitle($title);    
        return $playlistItemSnippet;
    }



}