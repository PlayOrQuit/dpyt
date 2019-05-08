<?php


namespace App\Data\Repository;


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
}