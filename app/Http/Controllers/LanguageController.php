<?php

namespace App\Http\Controllers;

use App\Language;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get languages
     */
    public function get(Request $req){
        try{
            $languages = Language::select('id', 'name', 'hl')->get();
            return $this->_resJsonSuccess('Success', $req->path(), $languages);
        }catch (QueryException $e){
            Log::error($e->getMessage(), $e->getTrace());
            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }
    }
}
