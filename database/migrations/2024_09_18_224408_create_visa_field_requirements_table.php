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
        Schema::create('visa_field_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('field_name');
            $table->boolean('is_required')->default(false);
            $table->unsignedBigInteger('visa_type_id')->nullable();
            $table->unsignedBigInteger('destination_id')->nullable();
            $table->timestamps();
    
            $table->foreign('visa_type_id')->references('id')->on('visa_types')->onDelete('cascade');
            $table->foreign('destination_id')->references('id')->on('destinations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visa_field_requirements');
    }
};
