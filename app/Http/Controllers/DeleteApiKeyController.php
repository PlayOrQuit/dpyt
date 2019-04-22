<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeleteApiKeyController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Delete api-key
     */
    public function delete(Request $req){

    }
}
