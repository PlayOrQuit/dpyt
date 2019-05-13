<?php

namespace App\Http\Controllers;

use App\Api\YoutubePlaylistService;
use App\Data\Repository\ChannelRepository;
use App\Data\Repository\DataKeyRepository;
use App\Data\Repository\PlaylistRepository;
use Exception;
use Google_Exception;
use Google_Service_Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ListPlaylistController extends Controller
{
    protected $playlistRepository;
    protected $channelRepository;
    protected $dataKeyRepository;

    /**
     * ListPlaylistController constructor.
     * @param PlaylistRepository $playlistRepository
     * @param ChannelRepository $channelRepository
     * @param DataKeyRepository $dataKeyRepository
     */
    public function __construct(PlaylistRepository $playlistRepository,
                                ChannelRepository $channelRepository,
                                DataKeyRepository $dataKeyRepository)
    {
        $this->middleware('auth');
        $this->playlistRepository = $playlistRepository;
        $this->channelRepository = $channelRepository;
        $this->dataKeyRepository = $dataKeyRepository;
    }

    /**
     * Render view
     */
    public function render(){

        return view('admin.playlist.list-playlist');
    }

    /**
     * Get List Playlist
     * @param Request $req
     * @return JsonResponse
     */
    public function get(Request $req){
        $userId = \Auth::user()->id;
        try
        {
            $listPlaylist = $this->playlistRepository->find($userId, array(
                'id',
                'uid',
                'title',
                'description',
                'video_count',
                'status_video',
                'keywords'
            ));

            return $this->_resJsonSuccess('Success', $req->path(), $listPlaylist);
        }
        catch (QueryException $e){
            Log::error($e->getMessage(), $e->getTrace());

            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }
    }

    /**
     * Delete Playlist
     * @param Request $req
     * @return JsonResponse
     */
    public function delete(Request $req){
        $userId = \Auth::user()->id;
        $params = $req->all();
        $validator = $this->validatorDelete($params);
        if ($validator->fails()) {

            return $this->_resJsonBad('Bad request', $req->path(), $validator->errors());
        }
        try{
            $playlist = $this->playlistRepository->findById($params['playlist_id'], $userId, array(
               'id',
               'uid',
               'channel_id'
            ));
            if($playlist){
                $channel = $this->channelRepository->findById($playlist['channel_id'], $userId, array(
                    'id',
                    'access_token',
                    'refresh_token',
                    'token_type',
                    'expires_in',
                    'iat',
                    'user_id'
                ));
                if($channel){
                    $dataKey = $this->dataKeyRepository->findByUserPrimary($userId, true, array('api_key', 'id_client', 'client_secret'));
                    if($dataKey){
                        $youtubePlaylistService = new YoutubePlaylistService();
                        $youtubePlaylistService->setDeveloperToken(array(
                            'access_token' => $channel['access_token'],
                            'refresh_token' => $channel['refresh_token'],
                            'created' => $channel['iat']->getTimestamp() - $channel['expires_in'],
                            'expires_in' => $channel['expires_in'],
                            'client_id' => $dataKey['id_client'],
                            'client_secret' => $dataKey['client_secret']
                        ));
                        $youtubePlaylistService->setChannel($channel);
                        $youtubePlaylistService->deletePlaylist($playlist['uid']);
                        $resultCode = $this->playlistRepository->delete($playlist['id'], $userId);
                        if($resultCode == true | $resultCode == 1){

                            return $this->_resJsonSuccess('Success', $req->path(), null);
                        }
                    }
                }
            }

            return $this->_resJsonBad('Bad Request', $req->path(), null);
        }catch (QueryException $e){
            Log::error($e->getMessage(), $e->getTrace());

            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }catch (Google_Service_Exception | Google_Exception $e){

            return $this->_resJsonYoutubeError($e->getMessage(), $req->path());

        }catch (Exception $e){
            Log::error($e->getMessage(), $e->getTrace());

            return $this->_resJsonErr($e->getMessage(), $req->path());
        }
    }

    /**
     * Pause auto add video to Playlist
     * @param Request $req
     * @return JsonResponse
     */
    public function pause(Request $req){
        $userId = \Auth::user()->id;
        $params = $req->all();
        $validator = $this->validatorPause($params);
        if ($validator->fails()) {

            return $this->_resJsonBad('Bad request', $req->path(), $validator->errors());
        }
        try{
            $resultCode = $this->playlistRepository->update($params['playlist_id'], $userId, array(
               'status_video' => $params['status_video']
            ));
            if($resultCode == true | $resultCode == 1){

                return $this->_resJsonSuccess('Success', $req->path(), null);
            }

            return $this->_resJsonBad('Bad Request', $req->path(), null);
        }catch (QueryException $e){
            Log::error($e->getMessage(), $e->getTrace());

            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }catch (Exception $e){
            Log::error($e->getMessage(), $e->getTrace());

            return $this->_resJsonErr($e->getMessage(), $req->path());
        }
    }

    public function editPlaylist(Request $req){
        $userId = \Auth::user()->id;
        $params = $req->all();
        $validator = $this->validatorEditPlaylist($params);
        if ($validator->fails()) {

            return $this->_resJsonBad('Bad request', $req->path(), $validator->errors());
        }
        try{
            $playlist = $this->playlistRepository->findById($params['playlist_id'], $userId, array(
                'id',
                'uid',
                'channel_id'
            ));
            if($playlist){
                $channel = $this->channelRepository->findById($playlist['channel_id'], $userId, array(
                    'id',
                    'access_token',
                    'refresh_token',
                    'token_type',
                    'expires_in',
                    'iat',
                    'user_id'

                ));
                if($channel){
                    $dataKey = $this->dataKeyRepository->findByUserPrimary($userId, true, array('api_key', 'id_client', 'client_secret'));
                    if($dataKey){
                        $youtubePlaylistService = new YoutubePlaylistService();
                        $youtubePlaylistService->setDeveloperToken(array(
                            'access_token' => $channel['access_token'],
                            'refresh_token' => $channel['refresh_token'],
                            'created' => $channel['iat']->getTimestamp() - $channel['expires_in'],
                            'expires_in' => $channel['expires_in'],
                            'client_id' => $dataKey['id_client'],
                            'client_secret' => $dataKey['client_secret']
                        ));
                        $youtubePlaylistService->setChannel($channel);
                        $youtubePlaylistService->updatePlaylist($playlist['uid'], $params['title'], $params['description'], $params['keywords']);
                        $resultCode = $this->playlistRepository->update($playlist['id'], $userId, array(
                            'title' => $params['title'],
                            'description' => $params['description'],
                            'keywords' => join(' | ', $params['keywords'])
                        ));
                        if($resultCode == true | $resultCode == 1){

                            return $this->_resJsonSuccess('Update Playlist success', $req->path(), null);
                        }
                    }
                }
            }
            return $this->_resJsonBad('Bad request', $req->path(), null);
        }catch (QueryException $e){
            Log::error($e->getMessage(), $e->getTrace());

            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }catch (Google_Service_Exception | Google_Exception $e){

            return $this->_resJsonYoutubeError($e->getMessage(), $req->path());

        }catch (Exception $e){
            Log::error($e->getMessage(), $e->getTrace());

            return $this->_resJsonErr($e->getMessage(), $req->path());
        }

    }

    private function validatorDelete($params){
        $rules = array(
            'playlist_id' => [
                'required',
                'integer'
            ]
        );
        return Validator::make($params, $rules);
    }

    private function validatorPause($params){
        $rules = array(
            'playlist_id' => [
                'required',
                'integer'
            ],
            'status_video' => [
                'required',
                'boolean'
            ],
        );
        return Validator::make($params, $rules);
    }

    public function validatorEditPlaylist($params){
        $rules = array(
            'playlist_id' => [
                'required',
                'integer'
            ],
            'keywords.*' => [
                'required',
                'string',
                'distinct'
            ],
            'title' => [
                'required',
                'string'
            ],
            'description' => [
                'string'
            ]
        );
        return Validator::make($params, $rules);
    }

}
