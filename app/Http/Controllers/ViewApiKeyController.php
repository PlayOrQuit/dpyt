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
        return view('admin.api_key.api-key');
    }
}