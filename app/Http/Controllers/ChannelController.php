<?php

namespace App\Http\Controllers;

use App\Channel;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ChannelController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Render view
     */
    public function render()
    {
        return view('admin.channel.channel');
    }

    /**
     * Render callback view
     */
    public function renderCallback()
    {
        return view('admin.channel.callback');
    }

    /**
     * Get channels
     */
    public function get(Request $req){
        $user_id = \Auth::user()->id;
        try
        {
            $channels = Channel::select('id', 'title', 'thumbnail', 'view', 'subscriber', 'status')->where(['user_id' => $user_id])->get();
            return $this->_resJsonSuccess('Success', $req->path(), $channels);
        }
        catch (QueryException $e){
            Log::error($e->getMessage(), $e->getTrace());
            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }
    }

    public function create(Request $req){
        $user_id = \Auth::user()->id;
        $data = $req->all();
        $validator = $this->validatorCreate($data);
        if ($validator->fails())
        {
            return $this->_resJsonBad('Bad request', $req->path(), $validator->errors());
        }
        try{
            $channel = Channel::where(['uid' => $data["uid"], 'user_id' => $user_id])->first();
            if($channel){
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
            }else{
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
                $channel->user_id = $user_id;
            }
            $channel->save();
            $channel->refresh();
            return $this->_resJsonSuccess(trans('message.create_success'), $req->path(), $channel->jsonSerialize());

        }catch (\Exception $e){
            Log::error($e->getMessage(), $e->getTrace());
            return $this->_resJsonErrDB( $e->getMessage(), $req->path());
        }
    }

    private function validatorCreate($data){
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

    public function delete(Request $req){
        $user_id = \Auth::user()->id;
        $validator = $this->validatorDelete($req->all());
        if ($validator->fails())
        {
            return $this->_resJsonBad('Bad request', $req->path(), $validator->errors());
        }
        try{
            Channel::where(['user_id' => $user_id])->whereIn('id', $req->items)->delete();
            return $this->_resJsonSuccess(trans('message.delete_success'), $req->path(), $req->items);
        }catch (QueryException $e){
            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }
    }

    private function validatorDelete($data){
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
