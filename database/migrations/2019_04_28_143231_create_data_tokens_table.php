<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('access_token', 150);
            $table->string('refresh_token', 150);
            $table->string('token_type', 15);
            $table->tinyInteger('expires_in');
            $table->timestamp('iat');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('data_key_id');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('data_key_id')->references('id')->on('data_keys')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_tokens');
    }
}
