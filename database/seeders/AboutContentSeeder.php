<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutContent;

class AboutContentSeeder extends Seeder
{
    public function run(): void
    {
        $contents = [
            [
                'section_key' => 'hero',
                'title' => 'Who We Are',
                'subtitle' => 'Leading Provider of Security & Construction Solutions',
                'content' => 'HTR ENGINEERING PTE LTD is a leading provider of roller shutters, security grilles, automatic gates, and comprehensive construction services in Singapore. With over 15 years of industry experience, we have established ourselves as a trusted name in the market.',
                'is_active' => true,
                'order' => 1
            ],
            [
                'section_key' => 'mission',
                'title' => 'Our Mission',
                'content' => 'To deliver superior quality roller shutters, security solutions, and construction services that exceed our clients\' expectations. We are committed to providing innovative, reliable, and cost-effective solutions while maintaining the highest standards of safety and professionalism.',
                'is_active' => true,
                'order' => 2
            ],
            [
                'section_key' => 'vision',
                'title' => 'Our Vision',
                'content' => 'To be Singapore\'s most trusted and preferred provider of security and construction solutions. We aspire to set industry benchmarks through continuous innovation, exceptional service quality, and sustainable business practices.',
                'is_active' => true,
                'order' => 3
            ]
        ];

        foreach ($contents as $content) {
            AboutContent::updateOrCreate(
                ['section_key' => $content['section_key']],
                $content
            );
        }
    }
}
