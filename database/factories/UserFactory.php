<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

    protected $model = \App\Models\User::class;
    public function definition(): array
    {
        return [
            'name' => $this->fake()->name(),
            'email' => $this->fake()->unique()->safeEmail(),
            'password' => Hash::make('12345678'),
            'phone' => $this->fake()->phoneNumber(),
            'address' => $this->fake()->address(),
            'photo' => $this->fake()->imageUrl(100,100,'people'),
            'role_id' => Role::inRandomOrder()->first()->id,
           
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
