<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaylistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uid', 150);
            $table->text('title');
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();
            $table->string('gl', 2);
            $table->string('hl', 10);
            $table->integer('video_count')->default(0);
            $table->boolean('status')->default(true);
            $table->boolean('status_video')->default(true);
            $table->boolean('status_filter')->default(false);
            $table->string('filter_by_date', 50)->nullable();
            $table->boolean('filter_by_date_status')->nullable();
            $table->integer('filter_by_duration')->nullable();
            $table->integer('filter_by_view')->nullable();
            $table->integer('filter_by_like')->nullable();
            $table->integer('filter_by_dislike')->nullable();
            $table->integer('search_video_count')->nullable();
            $table->string('channel_subscribe', 150)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('channel_id');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('playlists');
    }
}
