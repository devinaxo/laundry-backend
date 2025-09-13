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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->enum('status', ['pending', 'in_progress', 'ready', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('total', 10, 2)->default(0);
            $table->date('reception_date');
            $table->date('estimated_delivery_date')->nullable();
            $table->date('actual_delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
