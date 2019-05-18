<?php

use App\Data\Repository\Impl\PlaylistRepositoryImpl;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class PlaylistTableSeeder extends Seeder
{
    private $repository;

    public function __construct(\App\Data\Repository\PlaylistRepository $playlistRepository)
    {
        $this->repository = $playlistRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//       $this->repository->save(1, array(
//          'uid' => 'PLRTffvYJCkVHxo22Y_UmKN1d_npPBA5Qw',
//           'title' => 'Hướng dẫn trồng mai vàng',
//           'keywords' => 'mai vàng',
//           'gl' => 'VN',
//           'hl' => 'vi',
//           'video_count' => 11,
//           'channel_id' => 1
//       ));

    }
}
