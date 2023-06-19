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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('bearer_token')->nullable();
            $table->string('sender_name')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('technical_support_number')->nullable();
            $table->string('active_whatsapp_number')->nullable();
            $table->integer('tentative_period')->default(1);
            $table->double('tax')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
