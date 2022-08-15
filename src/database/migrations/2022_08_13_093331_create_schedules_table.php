<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->date('date');
            $table->time('venue_time', $precision = 0)->nullable();
            $table->time('start_time', $precision = 0)->nullable();
            $table->time('end_time', $precision = 0)->nullable();
            $table->text('description');
            $table->foreignId('stage_id')->constrained();
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
        Schema::dropIfExists('schedules');
    }
};
