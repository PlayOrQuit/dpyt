<?php


namespace App\Data\Repository;


use App\Playlist;

interface PlaylistRepository
{

    /**
     * @param $userId
     * @param $params
     * @return boolean
     */
    public function save($userId, $params);

    /**
     * @param $userId
     * @param $params
     * @return Playlist|mixed
     */
    public function saveRefresh($userId, $params);

    /**
     * @param $id
     * @param $userId
     * @param $params
     * @return boolean
     */
    public function update($id, $userId, $params);

    /**
     * @param $id
     * @param $userId
     * @return boolean
     */
    public function delete($id, $userId);

    public function findById(
        $id,
        $userId,
        $columns = array(
        'id',
        'uid',
        'title',
        'description',
        'keywords',
        'gl',
        'hl',
        'video_count',
        'status',
        'status_video',
        'status_filter',
        'filter_by_date',
        'filter_by_date_status',
        'filter_by_duration',
        'filter_by_view',
        'filter_by_like',
        'channel_id'));

    public function find($userId, $columns = array(
        'id',
        'uid',
        'title',
        'description',
        'keywords',
        'gl',
        'hl',
        'video_count',
        'status',
        'status_video',
        'status_filter',
        'filter_by_date',
        'filter_by_date_status',
        'filter_by_duration',
        'filter_by_view',
        'filter_by_like',
        'channel_id'));

    public function findAll($columns = array(
        'id',
        'uid',
        'title',
        'description',
        'keywords',
        'gl',
        'hl',
        'video_count',
        'status',
        'status_video',
        'status_filter',
        'filter_by_date',
        'filter_by_date_status',
        'filter_by_duration',
        'filter_by_view',
        'filter_by_like',
        'channel_id',
        'user_id'
    ));


    public function findChannelSubscribe($columns = array(
        'id',
        'uid',
        'title',
        'description',
        'keywords',
        'gl',
        'hl',
        'video_count',
        'status',
        'status_video',
        'status_filter',
        'filter_by_date',
        'filter_by_date_status',
        'filter_by_duration',
        'filter_by_view',
        'filter_by_like',
        'channel_id',
        'channel_subscribe',
        'user_id'
    ));

    public function findSubscribeIsNull($columns = array(
        'id',
        'uid',
        'title',
        'description',
        'keywords',
        'gl',
        'hl',
        'video_count',
        'status',
        'status_video',
        'status_filter',
        'filter_by_date',
        'filter_by_date_status',
        'filter_by_duration',
        'filter_by_view',
        'filter_by_like',
        'channel_id',
    ));

    public function findPlaylistSubscribeLast($channelId, $channelSubscribe, $columns = array(
        'id',
        'uid',
        'title',
        'description',
        'keywords',
        'gl',
        'hl',
        'video_count',
        'status',
        'status_video',
        'status_filter',
        'filter_by_date',
        'filter_by_date_status',
        'filter_by_duration',
        'filter_by_view',
        'filter_by_like',
        'channel_id',
    ));

    /**
     * @param $id
     * @param $userId
     * @param $params
     * @return boolean
     */
    public function updateView($uid, $playlist_view);

}