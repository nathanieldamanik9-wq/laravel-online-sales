<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_services', function (Blueprint $table) {
            $table->id();
            $table->string('courier', 50);
            $table->string('service', 50);
            $table->string('eta', 50)->nullable();
            $table->decimal('cost', 12, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_services');
    }
};