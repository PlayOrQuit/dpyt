<?php

namespace App\Console\Commands;

use App\Api\YoutubePlaylistService;
use App\Api\YoutubeSearch;
use App\Data\Repository\ChannelRepository;
use App\Data\Repository\DataKeyRepository;
use App\Data\Repository\PlaylistItemRepository;
use App\Data\Repository\PlaylistRepository;
use Exception;
use Google_Exception;
use Google_Service_Exception;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class SearchVideoFromChannel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:video_channel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search all video from channel';


    protected $playlistRepository;

    protected $playlistItemRepository;

    protected $dataKeyRepository;

    protected $channelRepository;

    /**
     * Create a new command instance.
     *
     * @param PlaylistRepository $playlistRepository
     * @param PlaylistItemRepository $playlistItemRepository
     * @param DataKeyRepository $dataKeyRepository
     * @param ChannelRepository $channelRepository
     */
    public function __construct(
        PlaylistRepository $playlistRepository,
        PlaylistItemRepository $playlistItemRepository,
        DataKeyRepository $dataKeyRepository,
        ChannelRepository $channelRepository
    )
    {
        parent::__construct();
        $this->playlistRepository = $playlistRepository;
        $this->playlistItemRepository = $playlistItemRepository;
        $this->channelRepository = $channelRepository;
        $this->dataKeyRepository = $dataKeyRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->runSearch();
    }

    private function runSearch(){
        $playlistSubscribes = $this->playlistRepository->findChannelSubscribe(array(
            'id',
            'channel_subscribe',
            'user_id',
            'channel_id'
        ));
        Log::debug(json_encode($playlistSubscribes));
        foreach ($playlistSubscribes as $playlist){
            try{
                $dataKey = $this->dataKeyRepository->findByUserPrimary($playlist['user_id'], true, array(
                    'api_key',
                    'id_client',
                    'client_secret'
                ));
                $channel = $this->channelRepository->findById($playlist['channel_id'], $playlist['user_id'], array(
                    'id',
                    'access_token',
                    'refresh_token',
                    'token_type',
                    'expires_in',
                    'iat',
                    'user_id'
                ));
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
                $youtubeSearch = new YoutubeSearch($dataKey->api_key);
                $searchResponse = $youtubeSearch->searchVideoByChannel($playlist['channel_subscribe'], null);
                $flag = true;
                Log::debug(json_encode($searchResponse));
                while ($flag){
                    Log::debug('line 1');
                    if(!isset($searchResponse['nextPageToken']) | !is_string($searchResponse['nextPageToken'])){
                        Log::debug('flag -> false');
                        $flag = false;
                    }
                    $videoIds = array();
                    foreach ($searchResponse['items'] as $searchResult){
                        $videoId = $searchResult['id']['videoId'];
                        if($videoId){
                            array_push($videoIds, $videoId);
                        }
                    }
                    $countVideo = count($videoIds);
                    if($countVideo > 0){
                        Log::debug('line 2');
                        if ($countVideo > 2){
                            $videoIdStr = join(',', $videoIds);
                        }else{
                            $videoIdStr = $videoIds[0];
                        }
                        $videoSearchResponse = $youtubeSearch->searchVideoById($videoIdStr, 'snippet,statistics');
                        foreach ($videoSearchResponse['items'] as $videoResponse){
                            $count = $this->playlistItemRepository->countByVideoId($videoResponse['id']);
                            if($count > 0){
                                Log::debug('line 3');
                                break 2;
                            }
                            Log::debug('line 4');
                            $playlistInsert = $this->playlistRepository->findPlaylistSubscribeLast($channel['id'], $playlist['channel_subscribe'], array(
                                'id',
                                'uid',
                                'video_count'
                            ));
                            if(!$playlistInsert){
                                Log::debug('line 5');
                                $sleepPlaylist =rand (120 , 300);
                                Log::debug('sleep playlist: '.$sleepPlaylist);
                                sleep($sleepPlaylist);
                                $playlistResponse = $youtubePlaylistService->createPlayList($videoResponse['snippet']['title'], app()->getLocale(), $videoResponse['snippet']['description']);
                                $paramInsert['uid'] = $playlistResponse['id'];
                                $paramInsert['title'] = $videoResponse['snippet']['title'];
                                $paramInsert['description'] = $videoResponse['snippet']['description'];
                                $paramInsert['channel_subscribe'] = $playlist['channel_subscribe'];
                                $arrTags = $videoResponse['snippet']['tags'];
                                if(count($arrTags) > 0){
                                    $paramInsert['keywords'] = join(' | ', $arrTags);
                                }
                                $paramInsert['gl'] = 'VN';
                                $paramInsert['hl'] = app()->getLocale();
                                $paramInsert['channel_id'] = $channel['id'];
                                $playlistInsert = $this->playlistRepository->saveRefresh($playlist['user_id'], $paramInsert);
                            }

//                            Log::debug('uid :' .  $playlistInsert['uid']);
                            Log::debug('-----------------------video id :' .  $videoResponse['id']);
//                            Log::debug('position :' .  $playlistInsert['video_count']);
//                            Log::debug('title :' .  $videoResponse['snippet']['title']);
//                            Log::debug('description :' . $videoResponse['snippet']['description']);
                            $sleepPlaylistItem =rand (60 , 120);
                            Log::debug('sleep PlaylistItem: '.$sleepPlaylistItem);
                            sleep($sleepPlaylistItem);
                            $playlistItemResult = $youtubePlaylistService->createPlaylistItem($playlistInsert['uid'], $videoResponse['id'], $playlistInsert['video_count'], $videoResponse['snippet']['title']);
                            $resultCode =  $this->playlistItemRepository->save($playlist['user_id'], array(
                                'uid' => $playlistItemResult['id'],
                                'video_uid' => $videoResponse['id'],
                                'position' => $playlistInsert['video_count'],
                                'channel_id' => $channel['id'],
                                'playlist_id' => $playlistInsert['id'],
                                'title' => $videoResponse['snippet']['title'],
                                'description' => $videoResponse['snippet']['description'],
                                'view_count' => $videoResponse['statistics']['viewCount'],
                                'like_count' => $videoResponse['statistics']['likeCount'],
                                'dislike_count' => $videoResponse['statistics']['dislikeCount'],
                                'favorite_count' => $videoResponse['statistics']['favoriteCount'],
                                'comment_count' => $videoResponse['statistics']['commentCount']
                            ));
                            if($resultCode == 1){
                                Log::debug('line 6');
                                $videoCount = $playlistInsert['video_count'];
                                $videoCount++;
                                $this->playlistRepository->update($playlistInsert['id'], $playlist['user_id'], array(
                                    'video_count' => $videoCount
                                ));
                            }
                        }
                    }
                    $searchResponse = $youtubeSearch->searchVideoByChannel($playlist['channel_subscribe'], $searchResponse['nextPageToken']);
                    Log::debug('line 7');
                    Log::debug(json_encode($searchResponse));
                }
            }catch (QueryException $e){
                Log::error($e->getMessage(), $e->getTrace());
            }catch (Google_Service_Exception | Google_Exception $e) {

            }catch (Exception $e){
                Log::error($e->getMessage(), $e->getTrace());
            }
        }
    }


}
