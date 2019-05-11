<?php

namespace App\Console\Commands;

use App\Api\YoutubePlaylistService;
use App\Api\YoutubeSearch;
use App\Data\Repository\ChannelRepository;
use App\Data\Repository\DataKeyRepository;
use App\Data\Repository\PlaylistItemRepository;
use App\Data\Repository\PlaylistRepository;
use Google_Exception;
use Google_Service_Exception;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class SearchVideoAddPlaylist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:search_video';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command search video add to playlist';

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
        $this->runSearchVideoAddPlaylist();
    }

    protected function runSearchVideoAddPlaylist()
    {
        try {
            $lstPlaylist = $this->playlistRepository->findAll();
            foreach ($lstPlaylist as $playlist) {
                Log::debug('line 1');
                $userId = $playlist['user_id'];
                $videoCount = $playlist['video_count'];
                $playlistItems = $this->playlistItemRepository->findByPlaylist($userId, $playlist['id'], array('video_uid'));
                if(count($playlistItems) < 25){
                    Log::debug('line 2');
                    $dataKey = $this->dataKeyRepository->findByUserPrimary($userId, true, array(
                        'api_key',
                        'id_client',
                        'client_secret'
                    ));
                    $channel = $this->channelRepository->findById($playlist['channel_id'], $userId, array(
                        'id',
                        'access_token',
                        'refresh_token',
                        'token_type',
                        'expires_in',
                        'iat',
                        'user_id'
                    ));
                    try {
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
                        $searchResponse = $youtubeSearch->searchKeyWord($playlist['keywords'], 10, $playlist['gl'], $playlist['hl']);
                        $arrVideoId = array();
                        foreach ($searchResponse['items'] as $searchResult) {
                            Log::debug($searchResult['id']['videoId']);
                            $videoId = $searchResult['id']['videoId'];
                            array_push($arrVideoId, $videoId);
                        }
                        $count = count($arrVideoId);
                        if ($count > 1) {
                            Log::debug('line 3');
                            if ($count > 2){
                                $videoIdStr = join(',', $arrVideoId);
                            }else{
                                $videoIdStr = $arrVideoId[0];
                            }
                            $videoSearchResponse = $youtubeSearch->searchVideoById($videoIdStr, 'snippet,contentDetails,statistics');
                            foreach ($videoSearchResponse['items'] as $videoResponse) {
                                Log::debug('line 4');
                                $isExits = false;
                                $arrPlaylistItems = json_decode($playlistItems);
                                $array = array_filter($arrPlaylistItems, function($item) use ($videoResponse) {
                                    return $item->video_uid == $videoResponse['id'];
                                });
                                if(count($array) > 0){
                                    $isExits = true;
                                }
                                Log::debug('$isExits run'.$isExits);
                                if (!$isExits) {
                                    if($playlist['status_filter'] == 1){
                                        Log::debug('filter run');
                                        if(is_string($playlist['filter_by_date'])){
                                            Log::debug('filter filter_by_date');
                                            $timeRes = strtotime($videoResponse['snippet']['publishedAt']);
                                            $dateRes = date('Y-m-d', $timeRes);
                                            $timePlay = strtotime($playlist['filter_by_date']);
                                            $datePlay = date('Y-m-d', $timePlay);
                                            if($playlist['filter_by_date_status'] == 1){
                                                if($datePlay < $dateRes){
                                                    continue;
                                                }
                                            }else{
                                                if($datePlay > $dateRes){
                                                    continue;
                                                }
                                            }
                                        }
                                        if(is_integer($playlist['filter_by_duration'])){
                                            Log::debug('filter_by_duration');
                                            if($this->getTime($videoResponse['contentDetails']['duration']) < $playlist['filter_by_duration']){
                                                continue;
                                            }
                                        }
                                        if(is_integer($playlist['filter_by_view'])){
                                            Log::debug('View: '.$playlist['filter_by_view']);
                                            Log::debug('View GG: '.((int)$videoResponse['statistics']['viewCount']));
                                            if(((int)$videoResponse['statistics']['viewCount']) < $playlist['filter_by_view']){
                                                continue;
                                            }
                                        }
                                        if(is_integer($playlist['filter_by_like'])){
                                            Log::debug('Like: '.$playlist['filter_by_like']);
                                            Log::debug('Like GG: '.((int)$videoResponse['statistics']['likeCount']));
                                            if(((int)$videoResponse['statistics']['likeCount']) < $playlist['filter_by_like']){
                                                continue;
                                            }
                                        }
                                        if(is_integer($playlist['filter_by_dislike'])){
                                            Log::debug('filter_by_dislike');
                                            if(((int)$videoResponse['statistics']['dislikeCount']) < $playlist['filter_by_dislike']){
                                                continue;
                                            }
                                        }
                                    }
                                    $playlistItemResult = $youtubePlaylistService->createPlaylistItem($playlist['uid'], $videoResponse['id'], $playlist['video_count'], $videoResponse['snippet']['title'], $videoResponse['snippet']['description']);
                                    $resultCode =  $this->playlistItemRepository->save($userId, array(
                                        'uid' => $playlistItemResult['id'],
                                        'video_uid' => $videoResponse['id'],
                                        'position' => $playlist['video_count'],
                                        'channel_id' => $channel['id'],
                                        'playlist_id' => $playlist['id'],
                                        'title' => $videoResponse['snippet']['title'],
                                        'description' => $videoResponse['snippet']['description'],
                                        'view_count' => $videoResponse['statistics']['viewCount'],
                                        'like_count' => $videoResponse['statistics']['likeCount'],
                                        'dislike_count' => $videoResponse['statistics']['dislikeCount'],
                                        'favorite_count' => $videoResponse['statistics']['favoriteCount'],
                                        'comment_count' => $videoResponse['statistics']['commentCount']
                                    ));
                                    Log::debug($resultCode);
                                    if($resultCode == 1){
                                        $videoCount++;
                                        $this->playlistRepository->update($playlist['id'], $userId, array(
                                            'video_count' => $videoCount
                                        ));
                                    }
                                }
                            }
                        }
                    } catch (Google_Service_Exception | Google_Exception $e) {

                    }
                }
            }
        } catch (QueryException $e) {
            Log::error($e->getMessage(), $e->getTrace());
        }
    }

    private function getTime($VidDuration){
        preg_match_all('/(\d+)/',$VidDuration,$parts);
        if(count($parts[0]) == 3){
            $time = ((int)$parts[0][0]) * 60 * 60;
            $time = $time + ((int)$parts[0][1]) * 60;
            return $time + ((int)$parts[0][2]);
        }
        if(count($parts[0]) == 2){
            $time = ((int)$parts[0][0]) * 60;
            return $time + ((int)$parts[0][1]);
        }
        return ((int)$parts[0][0]);
    }
}
