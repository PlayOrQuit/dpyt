<?php


namespace App\Data\Repository\Impl;


use App\Data\Repository\PlaylistItemRepository;
use App\PlaylistItem;

class PlaylistItemRepositoryImpl implements PlaylistItemRepository
{

    /**
     * @param $userId
     * @param $params
     * @return boolean
     */
    public function save($userId, $params)
    {
        $playlistItem = new PlaylistItem;
        $playlistItem->uid = $params['uid'];
        $playlistItem->video_uid = $params['video_uid'];
        if (isset($params['title']) && $params['title'])
            $playlistItem->title = $params['title'];
        if (isset($params['description']) && $params['description'])
            $playlistItem->description = $params['description'];
        if (isset($params['status']) && $params['status'])
            $playlistItem->status = $params['status'];
        if (isset($params['view_count']) && $params['view_count'])
            $playlistItem->view_count = $params['view_count'];
        if (isset($params['like_count']) && $params['like_count'])
            $playlistItem->like_count = $params['like_count'];
        if (isset($params['dislike_count']) && $params['dislike_count'])
            $playlistItem->dislike_count = $params['dislike_count'];
        if (isset($params['favorite_count']) && $params['favorite_count'])
            $playlistItem->favorite_count = $params['favorite_count'];
        if (isset($params['comment_count']) && $params['comment_count'])
            $playlistItem->comment_count = $params['comment_count'];
        $playlistItem->position = $params['position'];
        $playlistItem->channel_id = $params['channel_id'];
        $playlistItem->playlist_id = $params['playlist_id'];
        $playlistItem->user_id = $userId;
        return $playlistItem->save();
    }

    /**
     * @param $id
     * @param $userId
     * @param $params
     * @return boolean
     */
    public function update($id, $userId, $params)
    {
        return PlaylistItem::where(['id' => $id, 'user_id' => $userId])->update($params);
    }

    /**
     * @param $id
     * @param $userId
     * @return boolean
     */
    public function delete($id, $userId)
    {
        return PlaylistItem::where(['user_id' => $userId, 'id' => $id])->delete();
    }


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
    ))
    {
        return PlaylistItem::select($columns)->where(['user_id' => $userId, 'playlist_id' => $playlistId, 'video_uid' => $videoId])->first();
    }

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
    ))
    {
        return PlaylistItem::select($columns)->where(['user_id' => $userId, 'playlist_id' => $playlistId])->get();
    }

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
    ))
    {
        $limit = PlaylistItem::count();
        return PlaylistItem::select($columns)->where(['user_id' => $userId, 'playlist_id' => $playlistId])->skip($postion)->take($limit)->get();
    }
}
