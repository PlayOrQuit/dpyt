<?php

namespace App\Http\Controllers;

use App\DataKey;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class ViewApiKeyController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view_list()
    {
        $keys = DataKey::orderBy('updated_at', 'desc')->paginate(5);

        return view('admin.pages.user.view', compact('keys'));
    }


    /**
     * Post edit api-key
     */
    public function storeEdit(Request $req){

        $user_id = \Auth::user()->id;

        $validator = Validator::make($req->all(), [
            'api_key' => 'required|string|max:75',
            'primary' => 'required|boolean'
        ]);


        if ($validator->fails())
        {
            return back()->withInput();
        }

        $_api_key = DataKey::where(['api_key' => $req->old_api_key, 'user_id' => $user_id]);

        if(isset($_api_key))
        {
            abort(404);
        }

        $_api_key->api_key = $req->api_key;
        $_api_key->user_id = $user_id;


    }
}