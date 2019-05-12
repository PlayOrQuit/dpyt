<?php


namespace App\Data\Repository;


use App\PlaylistItem;

interface PlaylistItemRepository
{
    /**
     * @param $userId
     * @param $params
     * @return boolean
     */
    public function save($userId, $params);

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

    /**
     * @param $userId
     * @param $playlistId
     * @param $videoId
     * @param array $columns
     * @return PlaylistItem
     */
    public function find($userId, $playlistId, $videoId, $columns = array(
        'id',
        'uid',
        'video_uid',
        'title',
        'description',
        'status',
        'position',
        'view_count',
        'like_count',
        'dislike_count',
        'favorite_count',
        'comment_count',
        'channel_id',
        'playlist_id',
    ));

    /**
     * @param $userId
     * @param $playlistId
     * @param array $columns
     * @return PlaylistItem
     */
    public function getAllByChannelId($userId, $playlistId, $columns = array(
        'id',
        'uid',
        'video_uid',
        'title',
        'description',
        'status',
        'position',
        'view_count',
        'like_count',
        'dislike_count',
        'favorite_count',
        'comment_count',
        'channel_id',
        'playlist_id',
    ));

    /**
     * @param $userId
     * @param $playlistId
     * @param array $columns
     * @return PlaylistItem
     */
    public function getAllChannelUpdatePosition($userId, $playlistId, $postion, $columns = array(
        'id',
        'uid',
        'video_uid',
        'title',
        'description',
        'status',
        'position',
        'view_count',
        'like_count',
        'dislike_count',
        'favorite_count',
        'comment_count',
        'channel_id',
        'playlist_id',
    ));
}
