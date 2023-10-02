<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_read');
            $table->unsignedInteger('party_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->foreign('party_id')->references('id')->on('parties');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_groups');
    }
}
