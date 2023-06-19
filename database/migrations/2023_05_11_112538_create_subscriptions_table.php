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
        Schema::create('subscriptions', function (Blueprint $table) {
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
            $table->unsignedBigInteger('seller_code_id');
            $table->foreign('seller_code_id')
                ->references('id')
                ->on('seller_codes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('bank_id');
            $table->foreign('bank_id')
                ->references('id')
                ->on('banks')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->enum('type' , ['hotel' , 'branch'])->default('hotel');
            $table->enum('status' , ['active' , 'finished' , 'tentative' , 'tentative_finished'])->default('tentative');
            $table->double('amount')->default(0);
            $table->double('tax_value')->default(0);
            $table->double('discount_value')->default(0);
            $table->enum('subscription_type' , ['subscription' , 'renew'])->default('subscription');
            $table->string('transfer_photo')->nullable();
            $table->string('invoice_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->enum('payment_type' , ['bank' , 'online'])->default('bank');
            $table->enum('is_payment' , ['true' , 'false'])->default('false');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
