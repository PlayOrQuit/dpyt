<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uid', 50);
            $table->string('title', 150);
            $table->text('thumbnail');
            $table->integer('view')->default(0);
            $table->integer('subscriber')->default(0);
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('data_key_token');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('data_key_token')->references('id')->on('data_tokens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channels');
    }
}
