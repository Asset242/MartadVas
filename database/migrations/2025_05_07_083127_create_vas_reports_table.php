<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vas_reports', function (Blueprint $table) {
            $table->id();
            $table->string('service_id')->nullable();
            $table->string('price_point')->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_id')->nullable();
            $table->string('revenue')->nullable();
            $table->integer('count')->nullable();
            $table->date('date')->nullable();
            $table->string('transaction')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vas_reports');
    }
};
