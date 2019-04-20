<?php

namespace Tests\Unit;

use App\Facades\Youtube;
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
        Youtube::setApiKey('AIzaSyDz7l9VXfxfQSNzyTA-iwTiQ5sgT6ws1FM');
        $channels = Youtube::getChannels("ya29.GlvxBkYdLZNVDBa6YoD5IGb8Yt2524zPajrL2vyamt9fFgcSRBbbMlhRJZvmP1TMV8a5JMkjr6U9oZAFcHDR11wjgNo4k7Fq3K1Ymg8_pA3YflbhtXZ8-Pk4Oi_p");
        dd($channels);
    }


}
