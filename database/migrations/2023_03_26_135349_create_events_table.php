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
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ref_id')->unique()->nullable();
            $table->foreignUuid('calendar_id')->constrained()->onDelete('cascade');
            $table->string('description')->nullable();
            $table->string('summary')->nullable();
            $table->string('services');
            $table->longText('hangoutLink')->nullable();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->string('timezone');
            $table->enum('status', config('calendar.event.statuses'));

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
        Schema::dropIfExists('events');
    }
};
