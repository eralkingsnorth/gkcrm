<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LeadSource;

class LeadSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leadSources = [
            'Website',
            'Referral',
            'Social Media',
            'Advertising',
            'Cold Call',
            'Email Marketing',
            'Phone',
            'Trade Show',
            'Direct Mail',
            'Online Advertisement',
            'Google Ads',
            'Facebook Ads',
            'LinkedIn',
            'Word of Mouth',
            'Business Directory',
            'SC'
        ];

        foreach ($leadSources as $source) {
            LeadSource::firstOrCreate(['name' => $source]);
        }
    }
}
