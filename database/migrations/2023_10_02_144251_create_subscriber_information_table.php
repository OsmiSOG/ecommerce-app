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
        Schema::create('subscriber_information', function (Blueprint $table) {
            $table->id();
            $table->string('number_id');
            $table->string('client_id');
            $table->string('card_id');
            $table->string('card_token');
            $table->string('card_label');
            $table->string('card_franchise');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriber_information');
    }
};
