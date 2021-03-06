<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Data\Repository\ChannelRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ChannelController extends Controller
{

    protected $channelRepository;


    public function __construct(ChannelRepository $channelRepository)
    {
        $this->middleware('auth');
        $this->channelRepository = $channelRepository;
    }


    public function render()
    {
        return view('admin.channel.channel');
    }


    public function renderCallback()
    {
        return view('admin.channel.callback');
    }

    public function get(Request $req)
    {
        $user_id = \Auth::user()->id;
        try {
            $channels = $this->channelRepository->findByUser($user_id, array('id', 'title', 'thumbnail', 'view', 'subscriber', 'status'));
            return $this->_resJsonSuccess('Success', $req->path(), $channels);
        } catch (QueryException $e) {
            Log::error($e->getMessage(), $e->getTrace());
            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }
    }

    public function getByStatus(Request $req)
    {
        $user_id = \Auth::user()->id;
        try {
            $channels = $this->channelRepository->findByUserStatus($user_id, 0, array('id', 'title', 'thumbnail', 'view', 'subscriber'));
            return $this->_resJsonSuccess('Success', $req->path(), $channels);
        } catch (QueryException $e) {
            Log::error($e->getMessage(), $e->getTrace());
            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }
    }

    public function create(Request $req)
    {
        $user_id = \Auth::user()->id;
        $data = $req->all();
        $validator = $this->validatorCreate($data);
        if ($validator->fails()) {
            return $this->_resJsonBad('Bad request', $req->path(), $validator->errors());
        }
        try {
            $channel = Channel::where(['uid' => $data["uid"], 'user_id' => $user_id])->first();
            if ($channel) {
                $params = array(
                    "title" => $data["title"],
                    "thumbnail" => $data["thumbnail"],
                    "view" => $data["view"],
                    "subscriber" => $data["subscriber"],
                    "access_token" => $data["access_token"],
                    "refresh_token" => $data["refresh_token"],
                    "token_type" => $data["token_type"],
                    "expires_in" => $data["expires_in"],
                    "iat" => Carbon::createFromTimestamp($data["iat"] / 1000),
                );
                $result = $this->channelRepository->update($channel->id, $user_id, $params);
            } else {
                $params = array(
                    "uid" => $data["uid"],
                    "title" => $data["title"],
                    "thumbnail" => $data["thumbnail"],
                    "view" => $data["view"],
                    "subscriber" => $data["subscriber"],
                    "access_token" => $data["access_token"],
                    "refresh_token" => $data["refresh_token"],
                    "token_type" => $data["token_type"],
                    "expires_in" => $data["expires_in"],
                    "iat" => $data["iat"],
                );
                $result = $this->channelRepository->save($user_id, $params);
            }

            return $this->_resJsonSuccess(trans('message.create_success'), $req->path(), $result);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }
    }

    private function validatorCreate($data)
    {
        $rules = array(
            'uid' => [
                'required',
                'string',
                'max:50'
            ],
            'title' => [
                'required',
                'string',
                'max:150'
            ],
            'thumbnail' => [
                'required',
                'string'
            ],
            'view' => [
                'required',
                'string',
                'max:75'
            ],
            'subscriber' => [
                'required',
                'string',
                'max:75'
            ],
            'access_token' => [
                'required',
                'string',
                'max:150'
            ],
            'refresh_token' => [
                'required',
                'string',
                'max:150'
            ],
            'token_type' => [
                'required',
                'string',
                'max:15'
            ],
            'expires_in' => [
                'required',
                'numeric'
            ],
            'iat' => [
                'required',
                'numeric'
            ],
        );
        return Validator::make($data, $rules);
    }

    public function delete(Request $req)
    {
        $user_id = \Auth::user()->id;
        $validator = $this->validatorDelete($req->all());
        if ($validator->fails()) {
            return $this->_resJsonBad('Bad request', $req->path(), $validator->errors());
        }
        try {
            $result = $this->channelRepository->deleteList($req->items, $user_id);
            return $this->_resJsonSuccess(trans('message.delete_success'), $req->path(), $result);
        } catch (QueryException $e) {
            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }
    }

    private function validatorDelete($data)
    {
        $rules = array(
            'items' => [
                'required',
                'array',
                'min:1'
            ]
        );
        return Validator::make($data, $rules);
    }
}
