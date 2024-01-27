<?php

namespace Database\Seeders;

use App\Models\ApplicationSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $application_settings = ApplicationSettings::create([
            'app_version' => '1.0.0',
            'blog_name' => 'Jelajah Buku',
            'navbar_color' => 'var(--body-color)',
            'navbar_text_color' => 'var(--text-color)',
            'footer_color' => 'var(--body-color)',
            'footer_text_color' => 'var(--body-color)',
            'logo_filename' => 'testing.jpg',
            'email' => 'jelajahbuku2023@gmail.com',
            'phone_number' => '00011281217'
        ]);
    }
}
