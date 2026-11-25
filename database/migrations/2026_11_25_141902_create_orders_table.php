<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shipping_service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('recipient_name', 100);
            $table->string('phone', 30);
            $table->text('address');
            $table->string('shipping_courier', 50)->nullable();
            $table->string('shipping_service', 50)->nullable();
            $table->string('shipping_eta', 50)->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->string('status', 20)->default('pending');
            $table->timestamp('ordered_at')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};