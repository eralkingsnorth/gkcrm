<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $titles = ['Mr', 'Mrs', 'Miss', 'Ms', 'Dr', 'Prof'];
        $leadSources = \App\Models\LeadSource::pluck('name')->toArray();
        $maritalStatuses = ['single', 'married', 'divorced', 'widowed', 'civil_partnership', 'separated'];
        $countries = ['United Kingdom', 'United States', 'Canada', 'Australia', 'Germany', 'France', 'Spain', 'Italy'];

        return [
            'lead_source' => $this->faker->randomElement($leadSources),
            'title' => $this->faker->randomElement($titles),
            'forename' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'date_of_birth' => $this->faker->optional()->date('Y-m-d', '-18 years'),
            'country_of_birth' => $this->faker->randomElement($countries),
            'marital_status' => $this->faker->randomElement($maritalStatuses),
            'email_address' => $this->faker->unique()->safeEmail(),
            'mobile_number' => $this->faker->phoneNumber(),
            'home_phone' => $this->faker->optional(0.7)->phoneNumber(),
            'postcode' => $this->faker->optional()->postcode(),
            'house_number' => $this->faker->optional()->buildingNumber(),
            'address_line_1' => $this->faker->optional()->streetAddress(),
            'address_line_2' => $this->faker->optional(0.3)->secondaryAddress(),
            'address_line_3' => $this->faker->optional(0.2)->secondaryAddress(),
            'town_city' => $this->faker->optional()->city(),
            'county' => $this->faker->optional()->state(),
            'postcode_final' => $this->faker->optional()->postcode(),
            'country' => $this->faker->randomElement($countries),
            'other' => $this->faker->optional(0.2)->paragraph(),
            'notes' => $this->faker->optional(0.3)->randomElements([
                [
                    'id' => time(),
                    'content' => $this->faker->sentence(),
                    'date' => now()->toLocaleString()
                ]
            ], 1),
        ];
    }
}
