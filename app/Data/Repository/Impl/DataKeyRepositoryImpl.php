<?php


namespace App\Data\Repository\Impl;


use App\Data\Repository\DataKeyRepository;
use App\DataKey;

class DataKeyRepositoryImpl implements DataKeyRepository
{

    public function save($userId, $params)
    {
        $dataKey = new DataKey;
        $dataKey->api_key = $params["api_key"];
        $dataKey->id_client = $params["id_client"];
        $dataKey->client_secret = $params["client_secret"];
        $dataKey->user_id = $userId;
        $dataKey->save();
        return $dataKey;
    }


    public function update($id, $userId, $params)
    {
        return DataKey::where([ 'id' => $id, 'user_id' => $userId])->update($params);
    }

    public function updatePrimary($id, $userId, $primary)
    {
        DataKey::where(['user_id' => $userId, 'primary' => true])->update(['primary' => false]);
        return DataKey::where([ 'id' => $id, 'user_id' => $userId])->update(['primary' => true]);
    }

    public function delete($id, $userId)
    {
        return DataKey::where(['user_id' => $userId, 'id' => $id])->delete();
    }

    public function deleteList($ids, $userId)
    {
        return DataKey::where(['user_id' => $userId])->whereIn('id', $ids)->delete();
    }


    public function findByUser($userId, $columns = array('id', 'api_key', 'id_client', 'client_secret', 'primary'))
    {
        return DataKey::select($columns)->where(['user_id' => $userId])->get();
    }

    public function findByUserPrimary($userId, $primary, $columns = array('id', 'api_key', 'id_client', 'client_secret', 'primary'))
    {
        return DataKey::select($columns)->where(['user_id' => $userId, 'primary' => $primary])->get();
    }

}