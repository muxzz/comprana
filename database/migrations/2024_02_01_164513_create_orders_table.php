<?php

use App\Models\Order;
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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('dispatcher_id')->nullable();
            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->enum('status', Order::STATUS)->default('Procesando');
            $table->decimal('total', 15)->nullable();
            $table->string('invoice')->nullable();
            $table->string('address');
            $table->string('phone', 10);
            $table->integer('payment_id')->nullable();
            $table->string('payment_status')->nullable();

            $table->foreign('dispatcher_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('delivery_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
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
