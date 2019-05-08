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
        if(isset($params['title']) && $params['title'])
            $playlistItem->title = $params['title'];
        if(isset($params['description']) && $params['description'])
            $playlistItem->description = $params['description'];
        if(isset($params['status']) && $params['status'])
            $playlistItem->status = $params['status'];
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
        // TODO: Implement update() method.
    }

    /**
     * @param $id
     * @param $userId
     * @return boolean
     */
    public function delete($id, $userId)
    {
        // TODO: Implement delete() method.
    }
}