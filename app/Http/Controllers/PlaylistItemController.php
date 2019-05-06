<?php

namespace App\Http\Controllers;

use App\Api\YoutubePlaylistService;
use App\Data\Repository\ChannelRepository;
use App\Data\Repository\DataKeyRepository;
use App\Data\Repository\PlaylistItemRepository;
use App\Data\Repository\PlaylistRepository;
use Google_Exception;
use Google_Service_Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;


class PlaylistItemController extends Controller
{
    protected $playlistRepository;
    protected $channelRepository;
    protected $playlistItemRepository;
    protected $dataKeyRepository;

    public function __construct(PlaylistRepository $playlistRepository,
                                ChannelRepository $channelRepository,
                                PlaylistItemRepository $playlistItemRepository,
                                DataKeyRepository $dataKeyRepository
    )
    {
        $this->middleware('auth');
        $this->playlistRepository = $playlistRepository;
        $this->channelRepository = $channelRepository;
        $this->playlistItemRepository = $playlistItemRepository;
        $this->dataKeyRepository = $dataKeyRepository;
    }

    public function create(Request $req){
        $userId = \Auth::user()->id;
        $params = $req->all();
        $validator = $this->validatorCreate($params);
        if ($validator->fails()) {
            return $this->_resJsonBad('Bad request', $req->path(), $validator->errors());
        }
        try{
            $playlist = $this->playlistRepository->findById($params['playlistId'], $userId, array('id', 'uid', 'video_count', 'channel_id'));
            if($playlist){
                $channel = $this->channelRepository->findById($playlist['channel_id'], $userId, array(
                    'id',
                    'access_token',
                    'refresh_token',
                    'token_type',
                    'expires_in',
                    'iat'
                ));
                if($channel){
                    $dataKey = $this->dataKeyRepository->findByUserPrimary($userId, true, array('api_key', 'id_client', 'client_secret'));
                    if($dataKey){
                        $youtubePlaylistService = new YoutubePlaylistService();
                        $youtubePlaylistService->setDeveloperToken(array(
                            'access_token' => $channel['access_token'],
                            'refresh_token' => $channel['refresh_token'],
                            'created' => $channel['iat']->getTimestamp(),
                            'expires_in' => $channel['expires_in'],
                            'client_id' => $dataKey['id_client'],
                            'client_secret' => $dataKey['client_secret']
                        ));

                        $playlistItemResult = $youtubePlaylistService->createPlaylistItem($playlist['uid'], $params['videoId'], $playlist['video_count']);
                        $resultCode = $this->playlistItemRepository->save($userId, array(
                            'uid' => $playlistItemResult['id'],
                            'video_uid' => $params['videoId'],
                            'position' => $playlist['video_count'],
                            'channel_id' => $channel['id'],
                            'playlist_id' => $playlist['id']
                        ));
                        if($resultCode == true | $resultCode == 1){
                            $this->playlistRepository->update($playlist['id'], $userId, array(
                                'video_count' => $playlist['video_count'] + 1
                            ));
                            return $this->_resJsonSuccess('Insert PlaylistItem success', $req->path(), null);
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

    private function validatorCreate($params){
        $rules = array(
            'playlistId' => [
                'required',
                'integer'
            ],
            'videoId' => [
                'required',
                'string'
            ],
        );
        return Validator::make($params, $rules);
    }
}
