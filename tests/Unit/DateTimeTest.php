<?php

namespace Tests\Unit;


use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    function test(){
        //$date1 = Carbon::createFromFormat('Y-m-d', '2012-03-20', 'Asia/Ho_Chi_Minh');
//        $time = strtotime('2012-03-02T19:03:16.000Z');
//
//        $newformat = date('Y-m-d',$time);
//        $time1 = strtotime('2013-03-02T19:03:16.000Z');
//
//        $newformat1 = date('Y-m-d', $time1);
//       if($newformat1 > $newformat){
//           echo "> hon";
//       }else{
//           echo  "< hon";
//       }
      // echo $date1;
//        $date2 = Carbon::createFromFormat("yyyy-MM-dd", "2013-03-02");
//        assertFalse($date1 == $date2);

        echo $this->getTime('PT1H25M26S');
    }

    private function getTime($VidDuration){
        preg_match_all('/(\d+)/',$VidDuration,$parts);
        if(count($parts[0]) == 3){
            $time = ((int)$parts[0][0]) * 60 * 60;
            $time = $time + ((int)$parts[0][1]) * 60;
            return $time + ((int)$parts[0][2]);
        }
        if(count($parts[0]) == 2){
            $time = ((int)$parts[0][0]) * 60;
            return $time + ((int)$parts[0][1]);
        }
        return ((int)$parts[0][0]);
    }
}
