<?php

namespace App\Http\Controllers;


class ViewMultiplePlayListController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get multiple playlist
     */
    public function view_index(){
        return view('admin.playlist.multiple-playlist');
    }
}
