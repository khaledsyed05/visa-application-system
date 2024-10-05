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
        Schema::create('visa_types_destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visa_type_id')->constrained();
            $table->foreignId('destination_id')->constrained();
            $table->decimal('cost', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visa_types_destinations');
    }
};
