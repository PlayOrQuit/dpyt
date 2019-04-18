<?php

namespace App\Http\Controllers;

use App\DataKey;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
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
        Log::info("line 1");
        $user_id = \Auth::user()->id;

        Log::info("line 2");

        $validator = $this->validator($req->all(), $user_id);

        if ($validator->fails())
        {
            return Redirect::action('CreateApiKeyController@create')
                ->withErrors($validator)
                ->withInput();
        }

        try
        {
            $_api_key = new DataKey;
            $_api_key->api_key = $req->api_key;
            $_api_key->user_id = $user_id;
            $_api_key->save();

            Session::flash('success', trans('message.create_success'));
        }
        catch (Exception $e)
        {
            dd($e);
            Session::flash('failed', trans('message.create_failed'));
        }

        return redirect()->action('CreateApiKeyController@create');
    }

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
