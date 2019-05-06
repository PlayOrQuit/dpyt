<?php

namespace App\Http\Controllers;


use App\Api\AuthenticationException;
use App\Api\YoutubePlaylistService;
use App\Data\Repository\ChannelRepository;
use App\Data\Repository\PlaylistRepository;
use Google_Exception;
use Google_Service_Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class MultiplePlayListController extends Controller
{
    protected $playlistRepository;
    protected $channelRepository;

    public function __construct(PlaylistRepository $playlistRepository, ChannelRepository $channelRepository)
    {
        $this->middleware('auth');
        $this->playlistRepository = $playlistRepository;
        $this->channelRepository = $channelRepository;
    }

    public function view_index(){
        return view('admin.playlist.multiple-playlist');
    }

    public function create(Request $req){
        $user_id = \Auth::user()->id;
        $playlistReq = $req->only('playlist');
        if(count($playlistReq) == 0){

            return $this->_resJsonBad('Bad request', $req->path(), null);

        }
        try{
            $params = json_decode($playlistReq[0]);
            foreach ($params as $param){
                $channelId = $param['channel_id'];
                $channel = $this->channelRepository->findById($channelId, $user_id, array(
                    'id',
                    'access_token',
                    'refresh_token',
                    'token_type',
                    'expires_in',
                    'iat'
                ));
                if($channel){
                    $youtubePlaylistService = new YoutubePlaylistService();
                    $youtubePlaylistService->setChannel($channel);
                    $google_Service_YouTube_Playlist = $youtubePlaylistService->createPlayList($param->title, $param->description, $param->keywords);
                    $uid = $google_Service_YouTube_Playlist['id'];
                    $playlistParam = array(
                      'uid' => $uid,
                      'title' => $param['title'],
                      'description' => $param['description'],
                      'keywords' => join(',', $param['keywords']),
                       'gl' => $param['gl'],
                       'hl' => $param['hl'],
                        'channel_id' => $channelId
                    );
                    if($param['status_filter']){
                        $paramFilter = array(
                            'status_filter' => $param['status_filter'] ? $param['status_filter'] : null,
                            'filter_by_date' => $param['filter_by_date'] ? $param['filter_by_date']: null,
                            'filter_by_date_status' => $param['filter_by_date_status'] ? $param['filter_by_date_status'] : null,
                            'filter_by_duration' => $param['filter_by_duration'] ? $param['filter_by_duration'] : null,
                            'filter_by_view' => $param['filter_by_view'] ? $param['filter_by_view'] : null,
                            'filter_by_like' => $param['filter_by_like'] ? $param['filter_by_like'] : null,
                            'filter_by_dislike' => $param['filter_by_dislike'] ? $param['filter_by_dislike'] : null,
                        );
                        $playlistParam = array_merge($paramFilter, $playlistParam);
                    }
                    $result = $this->playlistRepository->save($user_id, $playlistParam);

                    return $this->_resJsonSuccess('Insert Success', $req->path(), $result);
                }
            }
        }catch (AuthenticationException $e){
            Log::error($e->getMessage(), $e->getTrace());

            return $this->_resJsonYoutubeError($e->getMessage(), $req->path());

        }catch (Google_Exception | Google_Service_Exception $e){
            Log::error($e->getMessage(), $e->getTrace());

            return $this->_resJsonBad($e->getMessage(), $req->path(), null);

        }catch (Exception $e){
            Log::error($e->getMessage(), $e->getTrace());

            return $this->_resJsonErr($e->getMessage(), $req->path());

        }
    }

//    private function validatorCreate($params){
//        $rules = array(
//            'title' => [
//                'required',
//                'string'
//            ],
//            'description' => [
//                'string'
//            ],
//            'keywords' => [
//                'required',
//                'string'
//            ],
//            'gl' => [
//                'required',
//                'string',
//                'max:2',
//            ],
//            'hl' => [
//                'required',
//                'string',
//                'max:10',
//            ],
//            'status_filter' => [
//                'boolean'
//            ],
//            'filter_by_date' => [
//                'date_format:Y-m-dT00:00:00Z'
//            ],
//            'filter_by_date_status' => [
//                'boolean'
//            ],
//            'filter_by_duration' => [
//                'integer'
//            ],
//            'filter_by_view' => [
//                'integer'
//            ],
//            'filter_by_like' => [
//                'integer'
//            ],
//            'filter_by_dislike' => [
//                'integer'
//            ],
//        );
//        return Validator::make($params, $rules);
//    }
}
