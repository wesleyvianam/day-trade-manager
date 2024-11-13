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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10);
            $table->integer('quantity');
            $table->char('type');
            $table->integer('purchase_value');
            $table->integer('avarage_value')->nullable();;
            $table->integer('sale_value')->nullable();;
            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable();;
            $table->foreignId('user_id')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
