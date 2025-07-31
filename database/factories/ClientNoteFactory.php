<?php

namespace Database\Factories;

use App\Models\ClientNote;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientNoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClientNote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => Client::factory(),
            'content' => $this->faker->paragraphs(rand(1, 3), true),
            'created_by' => User::factory(),
        ];
    }

    /**
     * Create a note with specific content.
     */
    public function withContent($content)
    {
        return $this->state(function (array $attributes) use ($content) {
            return [
                'content' => $content,
            ];
        });
    }

    /**
     * Create a note for a specific client.
     */
    public function forClient($clientId)
    {
        return $this->state(function (array $attributes) use ($clientId) {
            return [
                'client_id' => $clientId,
            ];
        });
    }

    /**
     * Create a note by a specific user.
     */
    public function byUser($userId)
    {
        return $this->state(function (array $attributes) use ($userId) {
            return [
                'created_by' => $userId,
            ];
        });
    }
} 