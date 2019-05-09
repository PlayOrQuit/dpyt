<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaylistItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlist_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uid', 150);
            $table->string('video_uid', 150);
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->string('status', 10)->nullable();
            $table->integer('position');
            $table->integer('view_count')->default(0);
            $table->integer('like_count')->default(0);
            $table->integer('dislike_count')->default(0);
            $table->integer('favorite_count')->default(0);
            $table->integer('comment_count')->default(0);
            $table->unsignedBigInteger('channel_id');
            $table->unsignedBigInteger('playlist_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
            $table->foreign('playlist_id')->references('id')->on('playlists')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('playlist_items');
    }
}
