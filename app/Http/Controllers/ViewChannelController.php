<?php

namespace App\Http\Controllers;


class ViewChannelController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get channels
     */
    public function view_list(){
        return view('admin.channel.channel');
    }
}
