<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_keys', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('api_key')->unsigned();
            $table->timestamps();
            $table->primary(['user_id','api_key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_keys');
    }
}
