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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->foreign('hotel_id')
                ->references('id')
                ->on('hotels')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('package_id');
            $table->foreign('package_id')
                ->references('id')
                ->on('packages')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('branch_id');
            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->foreign('bank_id')
                ->references('id')
                ->on('banks')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->enum('type' , ['package' , 'service'])->default('package');
            $table->enum('payment_type' , ['bank' , 'online'])->default('bank');
            $table->enum('status' , ['new' , 'renew'])->default('new');
            $table->string('details')->nullable();
            $table->string('transfer_photo')->nullable();
            $table->string('invoice_id')->nullable();
            $table->double('paid_amount')->default(0);
            $table->double('discount_value')->default(0);
            $table->double('tax_value')->default(0);
            $table->date('operation_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
