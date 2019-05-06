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
        if(array_key_exists('description', $params) && $params['description'])
            $playlist->description = $params['description'];
        if(array_key_exists('video_count', $params) && $params['video_count'])
            $playlist->video_count = $params['video_count'];
        $playlist->keywords = $params['keywords'];
        $playlist->gl = $params['gl'];
        $playlist->hl = $params['hl'];
        if(array_key_exists('status_filter', $params) && $params['status_filter']){
            $playlist->status_filter = $params['status_filter'];
            if(array_key_exists('filter_by_date', $params) && $params['filter_by_date']){
                $playlist->filter_by_date = $params['filter_by_date'];
                $playlist->filter_by_date_status = $params['filter_by_date_status'];
            }
            if(array_key_exists('filter_by_duration', $params) && $params['filter_by_duration'])
                $playlist->filter_by_duration = $params['filter_by_duration'];
            if(array_key_exists('filter_by_view', $params) && $params['filter_by_view'])
                $playlist->filter_by_view = $params['filter_by_view'];
            if(array_key_exists('filter_by_like', $params) && $params['filter_by_like'])
                $playlist->filter_by_like = $params['filter_by_like'];
            if(array_key_exists('filter_by_dislike', $params) && $params['filter_by_dislike'])
                $playlist['filter_by_dislike'] = $params['filter_by_dislike'];
        }

        $playlist->channel_id = $params['channel_id'];
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
        return Playlist::where([ 'id' => $id, 'user_id' => $userId])->update($params);
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

    public function findById($id, $userId, $columns = array(
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
        'channel_id'))
    {
       return Playlist::select($columns)->where(['id' => $id, 'user_id' => $userId])->first();
    }


}