<?php


namespace App\Api;


use Google_Client;
use Google_Exception;
use Google_Service_Exception;
use Google_Service_YouTube;
use Google_Service_YouTube_Playlist;
use Google_Service_YouTube_PlaylistSnippet;
use Google_Service_YouTube_PlaylistStatus;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class YoutubeService
{
    private $client;
    private $youtube;
    private $channel;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->youtube = new Google_Service_YouTube($this->client);
    }

    /**
     * @param $channel
     */
    public function setChannel($channel){
        if($channel == null)
            throw new InvalidArgumentException("Invalid Channel");
        $this->channel = $channel;
        $this->setAccessToken($this->channel->access_token);
    }

    /**
     * @param $accessToken
     */
    private function setAccessToken($accessToken){
        if($accessToken == null)
            throw new InvalidArgumentException("Invalid Access Token");
        $this->client->setAccessToken($accessToken);
    }

    /**
     * @throws AuthenticationException
     */
    private function checkAuth(){
        if($this->client->getAccessToken() == null){
            throw new AuthenticationException("Not Found Access Token");
        }else if($this->client->isAccessTokenExpired()){
            if($this->channel->refresh_token == null)
                throw new AuthenticationException("Not Found Refresh Token");
            $creds = $this->client->refreshToken($this->channel->refresh_token);
        }
    }



    /**
     * @param $title
     * @param null $description
     * @param null $tags
     * @return Google_Service_YouTube_Playlist
     * @throws AuthenticationException
     * @throws Google_Service_Exception
     * @throws Google_Exception
     */
    public function createPlayList($title, $description = null, $tags = null){
        $this->checkAuth();
        try{
            $youTubePlaylist = new Google_Service_YouTube_Playlist();
            $youTubePlaylist->setSnippet($this->createPlaylistSnippet($title, $description, $tags));
            $youTubePlaylist->setStatus($this->createPlaylistStatus());
            $playlistResponse = $this->youtube->playlists->insert('snippet,status', $youTubePlaylist, array());
            return $playlistResponse;
        }catch (Google_Service_Exception $e){
            Log::error($e->getMessage(), $e->getTrace());
            throw $e;
        }catch (Google_Exception $e){
            Log::error($e->getMessage(), $e->getTrace());
            throw $e;
        }
    }

    private function createPlaylistSnippet($title, $description, $tags){
        $playlistSnippet = new Google_Service_YouTube_PlaylistSnippet();
        $playlistSnippet->setTitle($title);
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



}