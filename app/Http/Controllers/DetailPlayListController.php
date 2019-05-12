<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Data\Repository\PlaylistRepository;
use App\Data\Repository\PlaylistItemRepository;
use App\Data\Repository\ChannelRepository;
use App\Data\Repository\DataKeyRepository;
use Illuminate\Database\QueryException;
use App\Api\YoutubePlaylistService;
use Illuminate\Support\Facades\DB;

class DetailPlayListController extends Controller
{
    protected $playlistRepository;
    protected $playlistItemRepository;
    protected $channelRepository;
    protected $dataKeyRepository;

    public function __construct(
        PlaylistRepository $playlistRepository,
        PlaylistItemRepository $playlistItemRepository,
        ChannelRepository $channelRepository,
        DataKeyRepository $dataKeyRepository
    ) {
        $this->middleware('auth');
        $this->playlistRepository = $playlistRepository;
        $this->playlistItemRepository = $playlistItemRepository;
        $this->channelRepository = $channelRepository;
        $this->dataKeyRepository = $dataKeyRepository;
    }

    public function index()
    {
        return view('admin.playlist.detail-playlist');
    }

    public function getPlayList(Request $req)
    {
        $userId = \Auth::user()->id;
        $params = $req->all();
        try {
            $playlistItem = $this->playlistRepository->findById($params["id"], $userId);

            return $this->_resJsonSuccess('Success', $req->path(), $playlistItem);
        } catch (QueryException $e) {
            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }
    }

    public function getListVideo(Request $req)
    {
        $userId = \Auth::user()->id;
        $params = $req->all();
        try {
            $listVideo = $this->playlistItemRepository->getAllByChannelId($userId, $params['playlist_id']);

            return $this->_resJsonSuccess('Success', $req->path(), $listVideo);
        } catch (QueryException $e) {
            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }
    }

    /**
     * Params [id, video_uid, position, channel_id, playlist_id]
     */
    public function deleteVideo(Request $req)
    {
        $userId = \Auth::user()->id;
        $params = $req->all();
        try {
            // Get info channel
            $channel = $this->channelRepository->findById($params['channel_id'], $userId, array(
                'id',
                'access_token',
                'refresh_token',
                'token_type',
                'expires_in',
                'iat',
                'user_id'
            ));
            if ($channel) {
                $dataKey = $this->dataKeyRepository->findByUserPrimary($userId, true, array('api_key', 'id_client', 'client_secret'));
                if ($dataKey) {
                    // Update token youtube api
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

                    DB::beginTransaction();
                    // Delete video in playlist
                    $resultCode = $this->playlistItemRepository->delete($params["id"], $userId);
                    if ($resultCode == true | $resultCode == 1) {
                        // Get list video after position
                        $listItem = $this->playlistItemRepository->getAllChannelUpdatePosition(
                            $userId,
                            $params['playlist_id'],
                            $params['position']
                        );

                        // Update position
                        foreach ($listItem as $item) {
                            $paramsUpdate = array(
                                "position" => $item["position"] - 1
                            );
                            $this->playlistItemRepository->update($item["id"], $userId, $paramsUpdate);
                        }

                        // Update video_count playlist
                        $playlist = $this->playlistRepository->findById($params['playlist_id'], $userId, array('id', 'uid', 'video_count', 'channel_id'));
                        if ($playlist) {
                            $this->playlistRepository->update($params['playlist_id'], $userId, array(
                                'video_count' => $playlist['video_count'] - 1
                            ));
                        }

                        // Call service youtube api delete video in list
                        $youtubePlaylistService->deletePlaylistItem($params["video_uid"]);

                        DB::commit();
                        return $this->_resJsonSuccess('Success', $req->path(), null);
                    }
                }
            }


            DB::rollBack();
            return $this->_resJsonBad('Bad Request', $req->path(), null);
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }
    }

    /**
     * Params [id, video_uid, video_video_uid position_old, position_new, channel_id, playlist_id, playlist_uid]
     */
    public function updatePositionVideo(Request $req)
    {
        $userId = \Auth::user()->id;
        $params = $req->all();
        try {
            // Get info channel
            $channel = $this->channelRepository->findById($params['channel_id'], $userId, array(
                'id',
                'access_token',
                'refresh_token',
                'token_type',
                'expires_in',
                'iat',
                'user_id'
            ));
            if ($channel) {
                $dataKey = $this->dataKeyRepository->findByUserPrimary($userId, true, array('api_key', 'id_client', 'client_secret'));
                if ($dataKey) {
                    // Update token youtube api
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

                    DB::beginTransaction();

                    // Get list video after position
                    $listItem = $this->playlistItemRepository->getAllByChannelId(
                        $userId,
                        $params['playlist_id']
                    );

                    if ($params["position_new"] > $params["position_old"]) {
                        // position_old -> position_new
                        for ($idx = $params["position_old"]; $idx < $params["position_new"]; $idx++) {
                            $paramsUpdatePosition = array(
                                "position" => $listItem[$idx]["position"] - 1
                            );

                            $this->playlistItemRepository->update($listItem[$idx]["id"], $userId, $paramsUpdatePosition);
                        }
                    } else {
                        // position_new -> position_old
                        for ($idx = $params["position_new"] - 1; $idx < $params["position_old"]; $idx++) {
                            $paramsUpdatePosition = array(
                                "position" => $listItem[$idx]["position"] + 1
                            );

                            $this->playlistItemRepository->update($listItem[$idx]["id"], $userId, $paramsUpdatePosition);
                        }
                    }

                    // Update position video in playlist
                    $paramsUpdatePosition = array(
                        "position" => $params["position_new"] - 1
                    );
                    $this->playlistItemRepository->update($params["id"], $userId, $paramsUpdatePosition);

                    // Call service youtube api delete video in list
                    $youtubePlaylistService->updatePlaylistItem($params["video_uid"], $params["playlist_uid"], $params["video_video_uid"], $params["position_new"] - 1);

                    DB::commit();
                    return $this->_resJsonSuccess('Success', $req->path(), null);
                }
            }


            DB::rollBack();
            return $this->_resJsonBad('Bad Request', $req->path(), null);
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }
    }
}
