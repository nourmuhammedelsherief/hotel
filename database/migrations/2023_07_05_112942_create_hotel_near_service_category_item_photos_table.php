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
        Schema::create('hotel_near_service_category_item_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('near_item_id');
            $table->foreign('near_item_id')
                ->references('id')
                ->on('hotel_near_service_category_items')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_near_service_category_item_photos');
    }
};
