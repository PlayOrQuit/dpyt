<?php

namespace App\Http\Controllers;

use App\DataKey;
use App\Http\Constant\WebKeys;
use Exception;
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
     * render view
     */
    public function render()
    {
        return view('admin.api_key.api-key');
    }

    public function get(Request $req){
        $user_id = \Auth::user()->id;
        try
        {
            $keys = DataKey::select('id', 'api_key', 'id_client', 'client_secret')->where(['user_id' => $user_id])->get();
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
            $_api_key->id_client = $data["id_client"];
            $_api_key->client_secret = $data["client_secret"];
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
            'id_client'=> [
                'required',
                'string',
                'max:75'
            ],
            'client_secret'=> [
                'required',
                'string',
                'max:75'
            ]
        );
        return Validator::make($data, $rules);
    }

    /**
     * Edit api-key
     */
    public function edit(Request $req){

        $user_id = \Auth::user()->id;

        $validator = $this->validatorEdit($req->all());

        if ($validator->fails())
        {
            $this->_resJsonError(WebKeys::HTTP_BAD_REQUEST, 'Bad request', $validator->errors(), $req->path());
        }
        try{
            $primary = $req->primary;
            if($primary == true){
                DataKey::where(['user_id' => $user_id, 'primary' => true])->update(['primary' => false]);
            }
            $data_key = DataKey::where([ 'api_key' => $req->api_key, 'user_id' => $user_id])->update(['primary' => $primary])->fresh();
            $this->_resJsonSuccess(WebKeys::HTTP_OK, $data_key, trans('message.edit_success'), $req->path());
        }catch (Exception $e){
            $this->_resJsonError(WebKeys::HTTP_BAD_REQUEST, trans('message.edit_failed'), null, $req->path());
        }
    }

    /**
     * Check data edit
     */
    protected function validatorEdit($data){
        $rules = array(
            'api_key' => [
                'required',
                'string',
                'max:100'
            ],
            'primary' => [
                'required',
                'boolean',
            ]
        );
        return Validator::make($data, $rules);
    }
}
