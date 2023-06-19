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
        Schema::create('hotel_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('rate_branch_id');
            $table->foreign('rate_branch_id')
                ->references('id')
                ->on('hotel_rate_branches')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('message')->nullable();
            $table->integer('food')->default(1);
            $table->integer('place')->default(1);
            $table->integer('service')->default(1);
            $table->integer('reception')->default(1);
            $table->integer('speed')->default(1);
            $table->integer('staff')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_rates');
    }
};
