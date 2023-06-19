<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hotel_slider_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('slider_id');
            $table->foreign('slider_id')
                ->references('id')
                ->on('hotel_sliders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_slider_images');
    }
};
