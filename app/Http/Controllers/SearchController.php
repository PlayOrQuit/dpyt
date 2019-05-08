<?php

namespace App\Http\Controllers;

use App\Api\KeyNotFoundException;
use App\Api\YoutubeSearch;
use App\Data\Repository\DataKeyRepository;
use Google_Exception;
use Google_Service_Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
   protected $dataKeyRepository;

    public function __construct(DataKeyRepository $dataKeyRepository)
    {
        $this->middleware('auth');
        $this->dataKeyRepository = $dataKeyRepository;
    }

    public function getTags(Request $req){
        $q = Input::get('q');
        $regionCode = Input::get('regionCode', 'vn');
        $relevanceLanguage = Input::get('relevanceLanguage', 'vi');
        $user_id = \Auth::user()->id;
        $validator = $this->validatorSearchTags(array('q' => $q));
        if ($validator->fails())
        {
            return $this->_resJsonBad('Bad request', $req->path(), $validator->errors());
        }
        try{
            $dataKey = $this->dataKeyRepository->findByUserPrimary($user_id, true, array('api_key'));
            if($dataKey){
                $youtubeSearch = new YoutubeSearch($dataKey->api_key);
                $searchResponse = $youtubeSearch->searchKeyWord($q, 10, $regionCode, $relevanceLanguage);
                $arr = array();
                foreach ($searchResponse['items'] as $searchResult){
                    $videoId = $searchResult['id']['videoId'];
                    array_push($arr, $videoId);
                }
                $videoIds = join(',', $arr);
                $videoSearchResponse = $youtubeSearch->searchVideoById($videoIds);
                $tags = array();
                foreach ($videoSearchResponse['items'] as $videoResponse){
                    if($videoResponse['snippet']['tags']){
                        $video_tags = array_slice($videoResponse['snippet']['tags'], 0, 5, true);
                        $tags = array_merge($video_tags, $tags);
                    }
                }

                $result = array_merge(array_unique($tags));

                return $this->_resJsonSuccess('Success', $req->path(), $result);
            }else{
                return $this->_resJsonYoutubeError('Not Found API Key', $req->path());
            }

        }catch (QueryException $e){
            Log::error($e->getMessage(), $e->getTrace());
            return $this->_resJsonErrDB($e->getMessage(), $req->path());
        }catch (KeyNotFoundException | Google_Service_Exception | Google_Exception $e){
            Log::error($e->getMessage(), $e->getTrace());
            return $this->_resJsonErr($e->getMessage(), $req->path());
        }
    }

    private function validatorSearchTags($data){
        $rules = array(
            'q'=> [
                'required',
                'string'
            ]
        );
        return Validator::make($data, $rules);
    }
}
