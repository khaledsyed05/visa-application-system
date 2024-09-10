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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
        $table->unsignedBigInteger('visa_type_id');
        $table->unsignedBigInteger('destination_id');
        $table->string('applicant_name');
        $table->string('email');
        $table->string('passport_file');
        $table->string('photo_file');
        $table->string('additional_info')->nullable();
        $table->string('phone_number')->nullable();
        $table->string('status')->default('unpaid');
        $table->string('paypal_order_id')->nullable();
        $table->string('payment_id')->nullable();
        $table->timestamps();

        $table->foreign('visa_type_id')->references('id')->on('visa_types');
        $table->foreign('destination_id')->references('id')->on('destinations');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
