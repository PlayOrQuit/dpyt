<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function get(Request $req){

    }

}
