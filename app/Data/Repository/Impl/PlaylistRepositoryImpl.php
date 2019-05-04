<?php


namespace App\Data\Repository\Impl;


use App\Data\Repository\PlaylistRepository;
use App\Playlist;

class PlaylistRepositoryImpl implements PlaylistRepository
{

    /**
     * @param $userId
     * @param $params
     * @return boolean
     */
    public function save($userId, $params)
    {
        $playlist = new Playlist;
        $playlist->uid = $params['uid'];
        $playlist->title = $params['title'];
        if($params['description'])
            $playlist->description = $params['description'];
        $playlist->keywords = $params['keywords'];
        $playlist->gl = $params['gl'];
        $playlist->hl = $params['hl'];
        if($params['status_filter']){
            $playlist->status_filter = $params['status_filter'];
            if($params['filter_by_date']){
                $playlist->filter_by_date = $params['filter_by_date'];
                $playlist->filter_by_date_status = $params['filter_by_date_status'];
            }
            if($params['filter_by_duration'])
                $playlist->filter_by_duration = $params['filter_by_duration'];
            if($params['filter_by_view'])
                $playlist->filter_by_view = $params['filter_by_view'];
            if($params['filter_by_like'])
                $playlist->filter_by_like = $params['filter_by_like'];
            if($params['filter_by_dislike'])
                $playlist['filter_by_dislike'] = $params['filter_by_dislike'];
        }
        $playlist->user_id = $userId;

        return $playlist->save();
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