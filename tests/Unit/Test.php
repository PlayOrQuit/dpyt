<?php

namespace Tests\Unit;

use App\Data\Repository\Impl\PlaylistRepositoryImpl;
use Illuminate\Support\Facades\Log;
use PlaylistTest;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function test(){
        $playlistService = new PlaylistRepositoryImpl();
        $pl = $playlistService->findPlaylistSubscribeLast();
        Log::debug(json_encode($pl));

    }
}
