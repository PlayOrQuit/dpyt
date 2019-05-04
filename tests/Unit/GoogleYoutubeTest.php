<?php


namespace Tests\Unit;


use App\Api\YoutubeSearch;
use Exception;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class GoogleYoutubeTest extends TestCase
{
    public function testBasicTest()
    {
        try{
          $youtubeSearch = new YoutubeSearch('AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6ws1FM');
          $searchResponse = $youtubeSearch->searchVideoByKeyWord('raider 2019');
          $arr = array();
          foreach ($searchResponse['items'] as $searchResult){
              $videoId = $searchResult['id']['videoId'];
              array_push($arr, $videoId);
          }
          $videoIds = join(',', $arr);
          $videoSearchResponse = $youtubeSearch->searchVideoById($videoIds);
          foreach ($videoSearchResponse['items'] as $videoResponse){
              Log::debug('video id: ' . $videoResponse['id']);
              if($videoResponse['snippet']['tags']){
                  foreach($videoResponse['snippet']['tags'] as $tag){
                      Log::debug('tag : ' . $tag);
                  }
              }
          }
        }catch (Exception $e){
            Log::error($e->getMessage(), $e->getTrace());
        }
    }
}