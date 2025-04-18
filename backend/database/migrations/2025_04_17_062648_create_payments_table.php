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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // Change from unsignedInteger to unsignedBigInteger
            $table->unsignedBigInteger('client_id'); // 🚨 This must match clients.id type
            $table->decimal('client_paid', 8, 2);
            $table->decimal('insurance_paid', 8, 2);
            $table->timestamps();
    
            $table->foreign('client_id')
                  ->references('id')
                  ->on('clients')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
