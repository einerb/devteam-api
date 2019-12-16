<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id_emitter')->nullable();
            $table->string('action');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('user_id_receiver')->nullable();
            $table->foreign('user_id_emitter')->references('id')->on('users');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('user_id_receiver')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histories');
    }
}
