<?php


namespace App\Data\Repository;


interface PlaylistRepository
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

}