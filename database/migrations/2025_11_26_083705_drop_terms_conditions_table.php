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
        Schema::dropIfExists('terms_conditions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot recreate the table, data migration to policies already done
    }
};
