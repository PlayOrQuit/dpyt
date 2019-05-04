<?php


namespace App\Data\Repository;


interface ChannelRepository
{
    /**
     * @param $userId
     * @param $params
     * @return mixed
     */
    public function save($userId, $params);

    /**
     * @param $id
     * @param $userId
     * @param $params array (k => v)
     * @return mixed
     */
    public function update($id, $userId, $params);

    /**
     * @param $id
     * @param $userId
     * @return mixed
     */
    public function delete($id, $userId);

    /**
     * @param $ids
     * @param $userId
     * @return mixed
     */
    public function deleteList($ids, $userId);

    /**
     * @param $userId
     * @param array $columns
     * @return mixed
     */
    public function findByUser($userId, $columns = array('id', 'uid', 'title', 'thumbnail', 'view', 'subscriber', 'status', 'access_token', 'token_type'));

    /**
     * @param $userId
     * @param $status
     * @param array $columns
     * @return mixed
     */
    public function findByUserStatus($userId, $status, $columns = array('id', 'uid', 'title', 'thumbnail', 'view', 'subscriber', 'status', 'access_token', 'token_type'));

    /**
     * @param $channelId
     * @param $userId
     * @param array $columns
     * @return mixed
     */
    public function findById($channelId, $userId, $columns = array('id', 'uid', 'title', 'thumbnail', 'view', 'subscriber', 'status', 'access_token', 'token_type'));
}