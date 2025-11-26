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
        $defaultSettings = [
            'address' => "105 Sims Avenue #05-11\nChancerlodge Complex\nSingapore 387429",
            'business_hours_detail' => "Mon - Fri: 9:00 AM - 6:00 PM\nSaturday: 9:00 AM - 1:00 PM\nSunday: Closed",
            'address_line1' => '105 Sims Avenue #05-11 Chancerlodge Complex',
            'city' => 'Singapore',
            'postal_code' => '387429',
            'latitude' => '1.3274',
            'longitude' => '103.8779',
        ];

        foreach ($defaultSettings as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'address',
            'business_hours_detail',
            'address_line1',
            'city',
            'postal_code',
            'latitude',
            'longitude',
        ])->delete();
    }
};
