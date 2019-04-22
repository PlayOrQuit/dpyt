<?php

namespace Tests\Unit;

use App\Facades\Youtube;
use App\Language;
use App\Region;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
   {
//        Youtube::setApiKey('AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6ws1FM');
//        $channels = Youtube::getChannels("ya29.GlvxBkYdLZNVDBa6YoD5IGb8Yt2524zPajrL2vyamt9fFgcSRBbbMlhRJZvmP1TMV8a5JMkjr6U9oZAFcHDR11wjgNo4k7Fq3K1Ymg8_pA3YflbhtXZ8-Pk4Oi_p");
//        dd($channels);

        $string = file_get_contents("D://region.json");
        $result_json = json_decode($string, true);
        $arr = $result_json["items"];
        $result = "";
        foreach ($arr as $re){
            $tmp = $re["snippet"];
//            $result = $result ."___". $tmp["gl"] . " + " . $tmp["name"];
            $re = new Region;
            $re->name = $tmp["name"];
            $re->gl = $tmp["gl"];
            $re->save();
        }

        $string = file_get_contents("D://language.json");
        $result_json = json_decode($string, true);
        $arr = $result_json["items"];
        $result = "";
        foreach ($arr as $re){
            $tmp = $re["snippet"];
//            $result = $result ."___". $tmp["gl"] . " + " . $tmp["name"];
            $lang = new Language;
            $lang->name = $tmp["name"];
            $lang->hl = $tmp["hl"];
            $lang->save();
        }
    }


}
