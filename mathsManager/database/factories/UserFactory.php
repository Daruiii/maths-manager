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
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'student',
            'status' => 'active',
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

    /**
     * Indicate that the user is a pending teacher application.
     */
    public function teacherPending(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'teacher',
            'status' => 'pending_approval',
            'phone' => fake()->phoneNumber(),
            'location' => fake()->city() . ', France',
            'bio' => fake()->paragraphs(3, true),
            'teaching_level' => fake()->randomElement(['college', 'lycee', 'prepa', 'superieur', 'autre']),
            'diploma' => fake()->randomElement(['master', 'agregation', 'capes', 'doctorat', 'autre']),
            'calendly_invite_sent' => fake()->boolean(20),
        ]);
    }

    /**
     * Indicate that the user is an active teacher.
     */
    public function teacherActive(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'teacher',
            'status' => 'active',
            'phone' => fake()->phoneNumber(),
            'location' => fake()->city() . ', France',
            'bio' => fake()->paragraphs(2, true),
            'teaching_level' => fake()->randomElement(['college', 'lycee', 'superieur']),
            'diploma' => fake()->randomElement(['master', 'agregation', 'capes']),
        ])->afterCreating(function (User $user) {
            $admin_id = \App\Models\User::where('role', 'admin')->first()->id ?? \App\Models\User::factory()->create(['role' => 'admin'])->id;
            $user->teacherApplication()->create([
                'status' => 'approved',
                'reviewed_by' => $admin_id,
                'reviewed_at' => fake()->dateTimeBetween('-1 year', 'now'),
            ]);
        });
    }

    /**
     * Indicate that the user is a rejected teacher application.
     */
    public function teacherRejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'teacher',
            'status' => 'rejected',
            'phone' => fake()->phoneNumber(),
            'location' => fake()->city() . ', France',
            'bio' => fake()->paragraph(),
            'teaching_level' => fake()->randomElement(['college', 'lycee']),
            'diploma' => fake()->randomElement(['licence', 'autre']),
        ]);
    }
}
