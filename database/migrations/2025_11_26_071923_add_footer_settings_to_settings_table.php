<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert default footer settings
        DB::table('settings')->insert([
            [
                'key' => 'footer_tagline', 
                'value' => 'Your trusted partner for professional roller shutter solutions in Singapore. Quality installations, expert repairs, and reliable service since day one.',
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'key' => 'footer_services',
                'value' => json_encode([
                    'Roller Shutter Installation',
                    'Repair & Maintenance',
                    'Emergency Services',
                    'Custom Solutions',
                    'Consultation'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', ['footer_tagline', 'footer_services'])->delete();
    }
};
