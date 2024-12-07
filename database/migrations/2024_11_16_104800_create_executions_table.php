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
            $table->integer('purchase_value');
            $table->integer('purchase_dollar_value')->nullable();
            $table->integer('average_value')->nullable();
            $table->integer('sale_value')->nullable();
            $table->integer('sale_dollar_value')->nullable();
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
