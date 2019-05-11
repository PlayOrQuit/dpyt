<?php


namespace App\Data\Repository\Impl;


use App\Channel;
use App\Data\Repository\ChannelRepository;
use Illuminate\Support\Carbon;

class ChannelRepositoryImpl implements ChannelRepository
{
    /**
     * @param $userId
     * @param $data
     * @return bool|mixed
     */
    public function save($userId, $data)
    {
        $channel = new Channel;
        $channel->uid = $data["uid"];
        $channel->title = $data["title"];
        $channel->thumbnail = $data["thumbnail"];
        $channel->view = $data["view"];
        $channel->subscriber = $data["subscriber"];
        $channel->access_token = $data["access_token"];
        $channel->refresh_token = $data["refresh_token"];
        $channel->token_type = $data["token_type"];
        $channel->expires_in = $data["expires_in"];
        $date =  Carbon::createFromTimestamp($data["iat"] / 1000);
        $channel->iat = $date;
        $channel->user_id = $userId;
        return $channel->save();
    }

    /**
     * @param $id
     * @param $userId
     * @param array $params
     * @return mixed
     */
    public function update($id, $userId, $params)
    {
       return Channel::where([ 'id' => $id, 'user_id' => $userId])->update($params);
    }

    /**
     * @param $id
     * @param $userId
     * @return mixed
     */
    public function delete($id, $userId)
    {
        return Channel::where(['user_id' => $userId, 'id' => $id])->delete();
    }

    /**
     * @param $ids
     * @param $userId
     * @return mixed
     */
    public function deleteList($ids, $userId)
    {
        return Channel::where(['user_id' => $userId])->whereIn('id', $ids)->delete();
    }

    /**
     * @param $userId
     * @param array $columns
     * @return mixed
     */
    public function findByUser($userId, $columns = array('id', 'uid', 'title', 'thumbnail', 'view', 'subscriber', 'status', 'access_token', 'token_type'))
    {
        return Channel::select($columns)->where(['user_id' => $userId])->get();
    }

    /**
     * @param $userId
     * @param $status
     * @param array $columns
     * @return mixed
     */
    public function findByUserStatus($userId, $status, $columns = array('id', 'uid', 'title', 'thumbnail', 'view', 'subscriber', 'status', 'access_token', 'token_type'))
    {
        return Channel::select($columns)->where(['user_id' => $userId, 'status' => $status])->get();
    }

    /**
     * @param $channelId
     * @param $userId
     * @param array $columns
     * @return mixed
     */
    public function findById($channelId, $userId, $columns = array('id', 'uid', 'title', 'thumbnail', 'view', 'subscriber', 'status', 'access_token', 'token_type'))
    {
        return Channel::select($columns)->where(['id' => $channelId, 'user_id' => $userId])->first();
    }
}