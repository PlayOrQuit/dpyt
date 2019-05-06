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
            $table->string('uid', 150);
            $table->string('title', 150);
            $table->text('thumbnail');
            $table->string('view', 75);
            $table->string('subscriber', 75);
            $table->boolean('status')->default(false);
            $table->string('access_token', 150);
            $table->string('refresh_token', 150);
            $table->string('token_type', 15);
            $table->integer('expires_in');
            $table->timestamp('iat')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
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
        Schema::dropIfExists('channels');
    }
}
