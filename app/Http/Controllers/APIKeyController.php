<?php

namespace App\Http\Controllers;

use App\DataKey;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class APIKeyController extends Controller
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
        return view('admin.api_key.api-key');
    }

    /**
     * Get by user
     */
    public function get(Request $req){
        $user_id = \Auth::user()->id;
        try
        {
            $keys = DataKey::select('id', 'api_key', 'id_client', 'client_secret', 'primary')->where(['user_id' => $user_id])->get();
            return $this->_resJsonSuccess('Success', $req->path(), $keys);
        }
        catch (QueryException $e){
            Log::error($e->getMessage(), $e->getTrace());
            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }
    }

    /**
     * Get by user and primary
     */
    public function getKeyByPrimary(Request $req){
        $user_id = \Auth::user()->id;
        try
        {
            $keys = DataKey::select('api_key', 'id_client', 'client_secret')->where(['user_id' => $user_id, 'primary' => true])->get();
            return $this->_resJsonSuccess('Success', $req->path(), $keys);
        }
        catch (QueryException $e){
            Log::error($e->getMessage(), $e->getTrace());
            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }
    }

    /**
     * Create api-key
     */
    public function create(Request $req)
    {
        $user_id = \Auth::user()->id;
        $data = $req->all();
        $validator = $this->validatorCreate($data, $user_id);
        if ($validator->fails())
        {
            return $this->_resJsonBad('Bad request', $req->path(), $validator->errors());
        }
        try
        {
            $_api_key = new DataKey;
            $_api_key->api_key = $data["api_key"];
            $_api_key->id_client = '263498759299-to3jnbgjkcee9hfhain7t9av53l4vg0n.apps.googleusercontent.com';
            $_api_key->client_secret = 'WZA8s-yHLYUHIl3eicq9GcZ0';
            $_api_key->user_id = $user_id;
            $_api_key->save();
            return $this->_resJsonSuccess(trans('message.create_success'), $req->path(), $_api_key->jsonSerialize());
        }
        catch (QueryException $e)
        {
            Log::error($e->getMessage(), $e->getTrace());
            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }

    }


    /**
     * Check data create
     */
    private function validatorCreate($data, $user_id){
        $rules = array(
            'api_key'=> [
                'required',
                'string',
                'max:75',
                Rule::unique('data_keys')->where(function ($query) use($data, $user_id)
                {
                    return $query->where('api_key', $data['api_key'])->where('user_id', $user_id);
                })
            ],
//            'id_client'=> [
//                'required',
//                'string',
//                'max:75'
//            ],
//            'client_secret'=> [
//                'required',
//                'string',
//                'max:75'
//            ]
        );
        return Validator::make($data, $rules);
    }

    /**
     * Edit api-key
     */
    public function editPrimary(Request $req){

        $user_id = \Auth::user()->id;

        $validator = $this->validatorEditPrimary($req->all());

        if ($validator->fails())
        {
            return $this->_resJsonBad('Bad request', $req->path(), $validator->errors());
        }
        try{
            DataKey::where(['user_id' => $user_id, 'primary' => true])->update(['primary' => false]);
            $data_key = DataKey::where([ 'api_key' => $req->api_key, 'user_id' => $user_id])->update(['primary' => true]);
            return $this->_resJsonSuccess(trans('message.edit_success'), $req->path(), $data_key);
        }catch (QueryException $e){
            Log::error($e->getMessage(), $e->getTrace());
            return $this->_resJsonErrDB( $e->getMessage(), $req->path());
        }
    }

    /**
     * Check data edit
     */
    protected function validatorEditPrimary($data){
        $rules = array(
            'api_key' => [
                'required',
                'string',
                'max:100'
            ]
        );
        return Validator::make($data, $rules);
    }
    /**
     * Delete
     */
    public function delete(Request $req){
        $user_id = \Auth::user()->id;
        $validator = $this->validatorDelete($req->all());
        if ($validator->fails())
        {
            return $this->_resJsonBad('Bad request', $req->path(), $validator->errors());
        }
        try{
            DataKey::where(['user_id' => $user_id])->whereIn('api_key', $req->items)->delete();
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
