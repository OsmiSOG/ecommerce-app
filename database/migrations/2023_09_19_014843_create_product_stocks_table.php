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
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('serial');
            $table->boolean('automatic');
            $table->double('sold_price', 14, 2)->nullable();
            $table->dateTime('sold_at')->nullable();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('sale_id')->nullable()->constrained('sales');
            $table->foreignId('deal_id')->nullable()->constrained('product_deals');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};
