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
        if(isset($params['description']))
            $playlist->description = $params['description'];
        if(isset($params['video_count']))
            $playlist->video_count = $params['video_count'];
        $playlist->keywords = $params['keywords'];
        $playlist->gl = $params['gl'];
        $playlist->hl = $params['hl'];
        if(isset($params['status_filter'])){
            $playlist->status_filter = $params['status_filter'];
            if(isset($params['filter_by_date'])){
                $playlist->filter_by_date = $params['filter_by_date'];
                $playlist->filter_by_date_status = $params['filter_by_date_status'];
            }
            if(isset($params['filter_by_duration'])){
                $playlist->filter_by_duration = $params['filter_by_duration'];
            }
            if(isset($params['filter_by_view'])){
                $playlist->filter_by_view = $params['filter_by_view'];
            }
            if(isset($params['filter_by_like'])){
                $playlist->filter_by_like = $params['filter_by_like'];
            }
            if(isset($params['filter_by_dislike'])){
                $playlist['filter_by_dislike'] = $params['filter_by_dislike'];
            }

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
        return Playlist::where([ 'id' => $id, 'user_id' => $userId])->delete();
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


    public function find($userId, $columns = array(
        'id',
        'uid',
        'title',
        'description',
        'video_count',
        'status_video',
        'keywords'))
    {
       return Playlist::select($columns)->where(['user_id' => $userId])->get();
    }

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
    ))
    {
        return Playlist::select($columns)->get();
    }

    /**
     * @param $userId
     * @param $params
     * @return Playlist|mixed
     */
    public function saveRefresh($userId, $params)
    {
        $playlist = new Playlist;
        $playlist->uid = $params['uid'];
        $playlist->title = $params['title'];
        if(isset($params['description']))
            $playlist->description = $params['description'];
        if(isset($params['video_count']))
            $playlist->video_count = $params['video_count'];
        $playlist->keywords = $params['keywords'];
        $playlist->gl = $params['gl'];
        $playlist->hl = $params['hl'];
        if(isset($params['status_filter'])){
            $playlist->status_filter = $params['status_filter'];
            if(isset($params['filter_by_date'])){
                $playlist->filter_by_date = $params['filter_by_date'];
                $playlist->filter_by_date_status = $params['filter_by_date_status'];
            }
            if(isset($params['filter_by_duration'])){
                $playlist->filter_by_duration = $params['filter_by_duration'];
            }
            if(isset($params['filter_by_view'])){
                $playlist->filter_by_view = $params['filter_by_view'];
            }
            if(isset($params['filter_by_like'])){
                $playlist->filter_by_like = $params['filter_by_like'];
            }
            if(isset($params['filter_by_dislike'])){
                $playlist['filter_by_dislike'] = $params['filter_by_dislike'];
            }

        }

        $playlist->channel_id = $params['channel_id'];
        $playlist->user_id = $userId;
        $playlist->save();
        $playlist->refresh();
        return $playlist;
    }


    /**
     * @param $id
     * @param $playlist_view
     * @return boolean
     */
    public function updateView($uid, $playlist_view)
    {
        return Playlist::where(['uid' => $uid])->update(['view_count'=>$playlist_view]);
    }
}