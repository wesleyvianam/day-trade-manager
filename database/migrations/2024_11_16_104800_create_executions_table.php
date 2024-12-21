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
        Schema::create('executions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_id')->index()->constrained()->cascadeOnDelete();
            $table->char('type');
            $table->integer('gain')->nullable();
            $table->integer('start_value');
            $table->integer('start_dollar_value')->nullable();
            $table->integer('end_value')->nullable();
            $table->integer('end_dollar_value')->nullable();
            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentations');
    }
};
