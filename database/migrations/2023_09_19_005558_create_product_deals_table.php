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
        Schema::create('product_deals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('campaign');
            $table->string('percentage');
            $table->boolean('tax');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->foreignId('product_id')->constrained('products');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_deals');
    }
};
