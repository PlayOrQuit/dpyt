<?php

namespace App\Console\Commands;

use App\Api\YoutubePlaylistService;
use App\Api\YoutubeSearch;
use App\Data\Repository\ChannelRepository;
use App\Data\Repository\DataKeyRepository;
use App\Data\Repository\PlaylistItemRepository;
use App\Data\Repository\PlaylistRepository;
use Google_Exception;
use Google_Service_Exception;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class PlaylistUpdateView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_view_playlist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command update playlist view';

    protected $playlistRepository;


    /**
     * Constructor
     */
    public function __construct(
        PlaylistRepository $playlistRepository
    )
    {
        parent::__construct();
        $this->playlistRepository = $playlistRepository;
    }

     /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->updateViewPlaylist();
    }


    protected function updateViewPlaylist(){
        try {
            $lstPlaylist = $this->playlistRepository->findAll();
            foreach($lstPlaylist as $item){
                $count = $this->crawl_data($item["uid"]);

                $this->playlistRepository->updateView($item['uid'], $count[0]);
            }
        } catch(QueryException $e){
            Log::error($e->getMessage(), $e->getTrace());
        }
    }

    private function crawl_data($playlistId){
       $url = "https://www.youtube.com/playlist?list=".$playlistId;
        $pageContent = file_get_contents($url);
        $crawler = new Crawler($pageContent);

        $count = $crawler->filter('div#pl-header > div.pl-header-content > ul.pl-header-details > li:nth-child(3)')->text();
        $value = $this->get_numerics($count);

        return $value;
    }

    private function get_numerics ($str) {
        preg_match_all('/\d+/', $str, $matches);
        return $matches[0];
    }
}