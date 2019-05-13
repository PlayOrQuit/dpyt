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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class CopyPlaylistController extends Controller
{
    protected $playlistRepository;
    protected $channelRepository;
    protected $dataKeyRepository;
    protected $playlistItemRepository;

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

    public function copy(Request $req){
        $userId = \Auth::user()->id;
        $params = $req->all();
        $validator = $this->validatorCopy($params);
        if ($validator->fails()) {
            return $this->_resJsonBad('Bad request', $req->path(), $validator->errors());
        }
        try{
            DB::beginTransaction();
            $playlist = $this->playlistRepository->findById($params['playlist_id'], $userId);
            if($playlist){
                $channel = $this->channelRepository->findById($playlist['channel_id'], $userId, array(
                    'id',
                    'access_token',
                    'refresh_token',
                    'token_type',
                    'expires_in',
                    'iat'
                ));
                $dataKey = $this->dataKeyRepository->findByUserPrimary($userId, true, array('api_key', 'id_client', 'client_secret'));
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
                if(!isset($params['title']) || trim($params['title']) === ''){
                    $title = $playlist['title'];
                }else{
                    $title = $params['title'];
                }
                $tags = explode(' | ', $playlist['keywords']);
                $responsePlaylist = $youtubePlaylistService->createPlayList($title, $playlist['hl'], $playlist['description'], $tags);
                $paramsInsert = array(
                    'uid' => $responsePlaylist['id'],
                    'title' => $title,
                    'description' => $playlist['description'],
                    'keywords' => $playlist['keywords'],
                    'channel_subscribe' => $playlist['channel_subscribe'],
                    'gl' => $playlist['gl'],
                    'hl' => $playlist['hl'],
                    'video_count' => $playlist['video_count'],
                    'status' => $playlist['status'],
                    'status_video' => $playlist['status_video'],
                    'status_filter' => $playlist['status_filter'],
                    'filter_by_date' => $playlist['filter_by_date'],
                    'filter_by_date_status' => $playlist['filter_by_date_status'],
                    'filter_by_duration' => $playlist['filter_by_duration'],
                    'filter_by_view' => $playlist['filter_by_view'],
                    'filter_by_like' => $playlist['filter_by_like'],
                    'filter_by_dislike' => $playlist['filter_by_dislike'],
                    'channel_id' => $playlist['channel_id'],
                );
                $newPlaylist = $this->playlistRepository->saveRefresh($userId, $paramsInsert);
                if($newPlaylist){
                    $playlistItems = $this->playlistItemRepository->findByPlaylist($userId, $playlist['id']);
                    foreach ($playlistItems as $playlistItem){
                        $responsePlaylist = $youtubePlaylistService->createPlaylistItem($newPlaylist['uid'], $playlistItem['video_uid'], $playlistItem['position'], $playlistItem['title'], $playlistItem['description']);
                        $this->playlistItemRepository->save($userId, array(
                            'uid' => $responsePlaylist['id'],
                            'video_uid' => $playlistItem['video_uid'],
                            'position' => $playlistItem['position'],
                            'channel_id' => $playlistItem['channel_id'],
                            'playlist_id' => $newPlaylist['id'],
                            'title' => $playlistItem['title'],
                            'description' => $playlistItem['description'],
                            'view_count' => $playlistItem['view_count'],
                            'like_count' => $playlistItem['like_count'],
                            'dislike_count' => $playlistItem['dislike_count'],
                            'favorite_count' => $playlistItem['favorite_count'],
                            'comment_count' => $playlistItem['comment_count'],
                            'status' => 'public'
                        ));
                    }
                }

            }
            DB::commit();
            return $this->_resJsonSuccess('Success', $req->path(), null);
        }catch (Google_Service_Exception | Google_Exception $e){
            DB::rollBack();
            return $this->_resJsonYoutubeError($e->getMessage(), $req->path());
        }catch (QueryException $e){
            DB::rollBack();
            Log::debug($e->getMessage(), $e->getTrace());
            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }catch (Exception $e){
            DB::rollBack();
            Log::debug($e->getMessage(), $e->getTrace());
            return $this->_resJsonErr($e->getMessage(), $e->getTrace());
        }

    }

    private function validatorCopy($params){
        $rules = array(
            'playlist_id' => [
                'required',
                'integer'
            ],
            'title' => [
                'string'
            ],
        );
        return Validator::make($params, $rules);
    }
}
