<?php

namespace App\Http\Controllers;

use App\Region;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegionController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get regions
     */
    public function get(Request $req){
        try{
            $regions = Region::select('id', 'name', 'gl')->get();
            return $this->_resJsonSuccess('Success', $req->path(), $regions);
        }catch (QueryException $e){
            Log::error($e->getMessage(), $e->getTrace());
            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }
    }
}
