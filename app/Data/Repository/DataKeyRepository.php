<?php


namespace App\Data\Repository;


interface DataKeyRepository
{
    /**
     * @param $id
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
     * @param $primary
     * @return mixed
     */
    public function updatePrimary($id, $userId, $primary);

    /**
     * @param $id
     * @param $userId
     * @return mixed
     */
    public function delete($id, $userId);

    /**
     * @param $ids
     *
     * @param $userId
     * @return mixed
     */
    public function deleteList($ids, $userId);

    /**
     * @param $userId
     * @param array $columns
     * @return mixed
     */
    public function findByUser($userId, $columns = array('id', 'api_key', 'id_client', 'client_secret', 'primary'));

    /**
     * @param $userId
     * @param $primary
     * @param array $columns
     * @return mixed
     */
    public function findByUserPrimary($userId, $primary, $columns = array('id', 'api_key', 'id_client', 'client_secret', 'primary'));
}