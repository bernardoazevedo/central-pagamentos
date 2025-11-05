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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clients_id');
            $table->foreign('clients_id')->references('id')->on('clients');
            $table->foreignId('gateways_id');
            $table->foreign('gateways_id')->references('id')->on('gateways');
            $table->string('external_id');
            $table->string('status');
            $table->bigInteger('amount');
            $table->string('card_last_numbers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
