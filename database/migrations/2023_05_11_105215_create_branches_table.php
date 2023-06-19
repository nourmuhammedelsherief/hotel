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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->enum('status' , ['active' , 'finished' , 'tentative' , 'tentative_finished'])->default('tentative');
            $table->enum('main' , ['true' , 'false'])->default('false');
            $table->enum('archive' , ['true' , 'false'])->default('false');
            $table->string('name_ar')->nullable();
            $table->string('name_en')->nullable();
            $table->string('subdomain')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('password')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->bigInteger('views')->default(0);
            $table->enum('tax' , ['true', 'false'])->default('false');
            $table->double('tax_value')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
