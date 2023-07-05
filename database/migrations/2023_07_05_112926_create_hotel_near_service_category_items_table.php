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
        Schema::create('hotel_near_service_category_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_near_cat_id');
            $table->foreign('hotel_near_cat_id')
                ->references('id')
                ->on('hotel_near_service_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('name_ar')->nullable();
            $table->string('name_en')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_near_service_category_items');
    }
};
