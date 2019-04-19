<?php

namespace App\Http\Controllers;

use App\DataKey;
use App\Http\Constant\WebKeys;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CreateApiKeyController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Post create api-key
     */
    public function create(Request $req)
    {
        $user_id = \Auth::user()->id;

        $validator = $this->validator($req->all(), $user_id);

        if ($validator->fails())
        {
            $this->_resJsonError(WebKeys::HTTP_BAD_REQUEST, 'Bad request', $validator->errors(), $req->path());
        }

        try
        {
            $_api_key = new DataKey;
            $_api_key->api_key = $req->api_key;
            $_api_key->user_id = $user_id;
            $_api_key->save();

            $this->_resJsonSuccess(WebKeys::HTTP_OK, $_api_key, trans('message.create_success'), $req->path());
        }
        catch (Exception $e)
        {
            $this->_resJsonError(WebKeys::HTTP_BAD_REQUEST, trans('message.create_failed'), null, $req->path());
        }

    }

    /**
     * Check Data
     */
    protected function validator($data, $user_id){
        $rules = array(
            'api_key'=> [
                'required',
                'string',
                'max:75',
                Rule::unique('data_keys')->where(function ($query) use($data, $user_id)
                {
                    $query->where('api_key', $data['api_key'])->where('user_id', $user_id);
                })
            ]
        );
        return Validator::make($data, $rules);
    }

}
