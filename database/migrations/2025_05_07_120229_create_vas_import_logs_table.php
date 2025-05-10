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
        Schema::create('vas_import_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('imported_at');
            $table->unsignedInteger('records_inserted')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vas_import_logs');
    }
};
