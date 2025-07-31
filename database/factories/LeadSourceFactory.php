<?php

namespace Database\Factories;

use App\Models\LeadSource;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadSourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LeadSource::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
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
            'Business Directory'
        ];

        return [
            'name' => $this->faker->unique()->randomElement($leadSources),
        ];
    }
}
