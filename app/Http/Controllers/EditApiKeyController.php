<?php

namespace App\Http\Controllers;

use App\DataKey;
use App\Http\Constant\WebKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EditApiKeyController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit(Request $req){

        $user_id = \Auth::user()->id;

        $validator = $this->validator($req->all());

        if ($validator->fails())
        {
            $this->_resJsonError(WebKeys::HTTP_BAD_REQUEST, 'Bad request', $validator->errors(), $req->path());
        }

        DataKey::where([ 'api_key' => $req->api_key, 'user_id' => $user_id, 'primary' => true])->update(['primary' => false]);


    }

    protected function validator($data){
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
