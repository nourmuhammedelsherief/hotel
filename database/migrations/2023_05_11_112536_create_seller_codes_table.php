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
        Schema::create('seller_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('marketer_id')->nullable();
            $table->foreign('marketer_id')
                ->references('id')
                ->on('marketers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('package_id')->nullable();
            $table->foreign('package_id')
                ->references('id')
                ->on('packages')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('seller_name')->nullable();
            $table->enum('permanent' , ['true' , 'false'])->default('false');
            $table->enum('status' , ['active' , 'finished'])->default('finished');
            $table->double('percentage')->default(0);
            $table->double('code_percentage')->default(0);
            $table->double('commission')->default(0);
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->enum('type' , ['hotel' , 'branch' , 'service' , 'all'])->default('hotel');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_codes');
    }
};
