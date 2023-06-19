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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar')->nullable();
            $table->string('name_en')->nullable();
            $table->string('subdomain')->nullable();
            $table->enum('status' , ['active' , 'tentative' , 'tentative_finished' ,'finished' , 'in_complete'])->default('in_complete');
            $table->enum('archive' , ['true' , 'false'])->default('false');
            $table->string('logo')->nullable();
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
            $table->unsignedBigInteger('package_id');
            $table->foreign('package_id')
                ->references('id')
                ->on('packages')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('phone_verification')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->enum('lang' , ['ar' , 'en' , 'both'])->default('ar');
            $table->enum('tax' , ['true' , 'false'])->default('false');
            $table->double('tax_value')->default(0);
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->bigInteger('views')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
