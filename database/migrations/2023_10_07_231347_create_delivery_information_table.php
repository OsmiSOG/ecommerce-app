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
        Schema::create('delivery_information', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('identification_type');
            $table->string('identification_number');
            $table->string('number_phone');
            $table->string('country');
            $table->string('city');
            $table->string('address');
            $table->string('details');
            $table->foreignId('sale_id')->constrained('sales');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_information');
    }
};
