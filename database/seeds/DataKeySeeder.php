<?php

use Illuminate\Database\Seeder;

class DataKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $dataKeyRe = new \App\Data\Repository\Impl\DataKeyRepositoryImpl();
//        $params = array(
//          'api_key' => '1234567890',
//          'id_client' => '1234567890',
//          'client_secret' => '1234567890'
//        );
//        $result = $dataKeyRe->save(1, $params);
//        \Illuminate\Support\Facades\Log::debug($result->toJson());
//        $dataKeyRe->update(4, 1, $params);
//        $dataKeyRe->updatePrimary(4, 1, true);
//        $result = $dataKeyRe->delete(4, 1);
//        \Illuminate\Support\Facades\Log::debug($result);

        $channelRe = new \App\Data\Repository\Impl\ChannelRepositoryImpl();
        $params = array(
          'view' => '10',
        );
        $result = $channelRe->update(1, 1, $params);
        \Illuminate\Support\Facades\Log::debug($result);
    }
}
