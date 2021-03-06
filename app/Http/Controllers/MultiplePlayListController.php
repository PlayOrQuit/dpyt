<?php

namespace App\Http\Controllers;


use App\Api\AuthenticationException;
use App\Api\YoutubePlaylistService;
use App\Data\Repository\ChannelRepository;
use App\Data\Repository\PlaylistRepository;
use App\Data\Repository\DataKeyRepository;
use Google_Exception;
use Google_Service_Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class MultiplePlayListController extends Controller
{
    protected $playlistRepository;
    protected $channelRepository;
    protected $dataKeyRepository;

    public function __construct(
        PlaylistRepository $playlistRepository,
        ChannelRepository $channelRepository,
        DataKeyRepository $dataKeyRepository
    ) {
        $this->middleware('auth');
        $this->playlistRepository = $playlistRepository;
        $this->channelRepository = $channelRepository;
        $this->dataKeyRepository = $dataKeyRepository;
    }

    public function view_index()
    {
        return view('admin.playlist.multiple-playlist');
    }

    public function create(Request $req)
    {
        $user_id = \Auth::user()->id;
        $playlistReq = $req->only('playlist');
        if (count($playlistReq) == 0) {

            return $this->_resJsonBad('Bad request', $req->path(), null);
        }
        
        try {

            foreach ($playlistReq["playlist"] as $param) {
                
                $channelId = $param['channel_id'];
                $channel = $this->channelRepository->findById($channelId, $user_id, array(
                    'id',
                    'access_token',
                    'refresh_token',
                    'token_type',
                    'expires_in',
                    'iat'
                ));
                if ($channel) {
                    $dataKey = $this->dataKeyRepository->findByUserPrimary($user_id, true, array('api_key', 'id_client', 'client_secret'));
                    if ($dataKey) {
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
                        $google_Service_YouTube_Playlist = $youtubePlaylistService->createPlayList($param['title'], $param['hl'], $param['description'], $param['keywords']);
                        $uid = $google_Service_YouTube_Playlist['id'];
                        $playlistParam = array(
                            'uid' => $uid,
                            'title' => $param["title"],
                            'description' => $param['description'],
                            'keywords' => $param['keywords'],
                            'gl' => $param['gl'],
                            'hl' => $param['hl'],
                            'channel_id' => $channelId
                        );
                        if (array_key_exists("status_filter", $param) && $param['status_filter']) {
                            $paramFilter = array(
                                'status_filter' => array_key_exists("status_filter", $param) && !$this->IsNullOrEmptyString($param['status_filter']) ? $param['status_filter'] : null,
                                'filter_by_date' =>array_key_exists("filter_by_date", $param) && !$this->IsNullOrEmptyString($param['filter_by_date']) ? $param['filter_by_date'] : null,
                                'filter_by_date_status' => array_key_exists("filter_by_date_status", $param) && !$this->IsNullOrEmptyString($param['filter_by_date_status']) ? $param['filter_by_date_status'] : null,
                                'filter_by_duration' => array_key_exists("filter_by_duration", $param) && !$this->IsNullOrEmptyString($param['filter_by_duration']) ? $param['filter_by_duration'] : null,
                                'filter_by_view' => array_key_exists("filter_by_view", $param) && !$this->IsNullOrEmptyString($param['filter_by_view']) ? $param['filter_by_view'] : null,
                                'filter_by_like' => array_key_exists("filter_by_like", $param) && !$this->IsNullOrEmptyString($param['filter_by_like']) ? $param['filter_by_like'] : null,
                                'filter_by_dislike' => array_key_exists("filter_by_dislike", $param) && !$this->IsNullOrEmptyString($param['filter_by_dislike']) ? $param['filter_by_dislike'] : null,
                            );
                            $playlistParam = array_merge($paramFilter, $playlistParam);
                        }
                        $result = $this->playlistRepository->save($user_id, $playlistParam);
                    }
                }
            }
            return $this->_resJsonSuccess('Insert Success', $req->path(), $result);
        } catch (AuthenticationException $e) {
            Log::error($e->getMessage(), $e->getTrace());

            return $this->_resJsonYoutubeError($e->getMessage(), $req->path());
        } catch (Google_Exception | Google_Service_Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());

            return $this->_resJsonBad($e->getMessage(), $req->path(), null);
        } catch (Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());

            return $this->_resJsonErr($e->getMessage(), $req->path());
        }
    }

    private function IsNullOrEmptyString($str){
        return (!isset($str) || trim($str) === '');
    }
}
