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
            $table->string('api_key', 75);
            $table->string('id_client', 75);
            $table->string('client_secret', 75);
            $table->timestamps();
            $table->primary(['user_id','api_key']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
