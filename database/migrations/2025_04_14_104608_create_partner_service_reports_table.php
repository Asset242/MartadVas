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
        Schema::create('partner_service_reports', function (Blueprint $table) {
            $table->id();
            $table->string('service_type')->nullable();
            $table->decimal('charge_amount', 10, 2)->nullable();
            $table->unsignedInteger('count')->nullable();
            $table->date('added_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_service_reports');
    }
};
