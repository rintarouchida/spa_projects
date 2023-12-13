<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartyTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('party_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('party_id');
            $table->unsignedInteger('tag_id');
            $table->timestamps();

            $table->foreign('party_id')->references('id')->on('parties');
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('party_tag');
    }
}
