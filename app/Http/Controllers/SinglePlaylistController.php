<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SinglePlaylistController extends Controller
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
    public function render(){
        return view('admin.playlist.single-playlist');
    }
}
