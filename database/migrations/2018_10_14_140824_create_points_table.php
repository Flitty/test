<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePointsTable
 */
class CreatePointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('figure_id');
            $table->unsignedInteger('order');
            $table->string('latitude');
            $table->string('longitude');
            $table->timestamps();
            $table->foreign('figure_id')->on('figures')->references('id')->onDelete('cascade');
            $table->unique(['figure_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('points');
    }
}
