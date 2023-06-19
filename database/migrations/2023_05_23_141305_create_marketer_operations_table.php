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
        Schema::create('marketer_operations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('marketer_id');
            $table->foreign('marketer_id')
                ->references('id')
                ->on('marketers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->foreign('subscription_id')
                ->references('id')
                ->on('subscriptions')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('seller_code_id')->nullable();
            $table->foreign('seller_code_id')
                ->references('id')
                ->on('seller_codes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->enum('status' , ['done' , 'not_done'])->default('not_done');
            $table->double('amount')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketer_operations');
    }
};
