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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Illuminate\Support\Facades\Validator;

class SinglePlaylistController extends Controller
{
    protected $playlistRepository;
    protected $channelRepository;
    protected $dataKeyRepository;

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
        return view('admin.playlist.single-playlist');
    }

    public function create(Request $req){
        $userId = \Auth::user()->id;
        $params = $req->all();
        $validator = $this->validatorCreate($params);
        if ($validator->fails()) {
            return $this->_resJsonBad('Bad request', $req->path(), $validator->errors());
        }
        try{

            $channel = $this->channelRepository->findById($params['channel_id'], $userId, array(
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
                        'created' => $channel['iat']->getTimestamp() - $channel['expires_in'],
                        'expires_in' => $channel['expires_in'],
                        'client_id' => $dataKey['id_client'],
                        'client_secret' => $dataKey['client_secret']
                    ));
                    $youtubePlaylistService->setChannel($channel);
                    $description = null;
                    if(isset($params['description'])){
                        $description = $params['description'];
                    }
                    $resultYoutubePlaylist = $youtubePlaylistService->createPlayList($params['title'], $description, $params['keywords']);

                    $paramInsert['uid'] = $resultYoutubePlaylist['id'];
                    $paramInsert['title'] = $params['title'];
                    $paramInsert['channel_id'] = $channel['id'];
                    if($description){
                        $paramInsert['description'] = $description;
                    }
                    $paramInsert['keywords'] = join(' | ', $params['keywords']);
                    $paramInsert['gl'] = 'VN';
                    $paramInsert['hl'] = 'vi';
                    if(isset($params['status_filter']) && $params['status_filter']){
                        if(!isset($params['filter_by_date']) &&
                            !isset($params['filter_by_duration']) &&
                            !isset($params['filter_by_view']) &&
                            !isset($params['filter_by_like']) &&
                            !isset($params['filter_by_dislike'])
                        ){
                            throw new InvalidArgumentException('Invalid params filters');
                        }
                        $paramInsert['status_filter'] = $params['status_filter'];
                        if(isset($params['filter_by_date'])){
                            $paramInsert['filter_by_date'] = $params['filter_by_date'].'T00:00:00Z';
                            $paramInsert['filter_by_date_status'] = $params['filter_by_date_status'];
                        }
                        if(isset($params['filter_by_duration'])){
                            $paramInsert['filter_by_duration'] = $params['filter_by_duration'];
                        }
                        if(isset($params['filter_by_view'])){
                            $paramInsert['filter_by_view'] = $params['filter_by_view'];
                        }
                        if(isset($params['filter_by_like'])){
                            $paramInsert['filter_by_like'] = $params['filter_by_like'];
                        }
                        if(isset($params['filter_by_dislike']) && $params['filter_by_dislike']){
                            $paramInsert['filter_by_dislike'] = $params['filter_by_dislike'];
                        }
                    }

                    $resultCode = $this->playlistRepository->save($userId, $paramInsert);
                    if($resultCode == true | $resultCode == 1){
                        return $this->_resJsonSuccess('Insert Playlist success', $req->path(), null);
                    }

                }
            }
            return $this->_resJsonBad('Bad request', $req->path(), null);
        }catch (Google_Exception | Google_Service_Exception $e){
            Log::error($e->getMessage(), $e->getTrace());
            return $this->_resJsonYoutubeError($e->getMessage(), $req->path());
        }catch (QueryException $e){
            Log::error($e->getMessage(), $e->getTrace());
            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }catch (Exception $e){
            Log::error($e->getMessage(), $e->getTrace());
            return $this->_resJsonErr($e->getMessage(), $req->path());
        }
    }

    private function validatorCreate($params){
        $rules = array(
            'channel_id' => [
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
            ],
            'status_filter' => [
                'boolean'
            ],
            'filter_by_date' => [
                'date_format:Y-m-d'
            ],
            'filter_by_date_status' => [
                'boolean'
            ],
            'filter_by_duration' => [
                'integer'
            ],
            'filter_by_view' => [
                'integer'
            ],
            'filter_by_like' => [
                'integer'
            ],
            'filter_by_dislike' => [
                'integer'
            ],
        );
        return Validator::make($params, $rules);
    }
}
