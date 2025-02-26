<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        static $password;

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $password ?: $password = bcrypt('secret'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalWorkspace(?callable $callback = null): static
    {
        return $this->has(
            Workspace::factory()
                ->state(fn (array $attributes, User $user) => [
                    'name' => $user->name.'\'s Team',
                    'user_id' => $user->id,
                    'personal_workspace' => true,
                ])
                ->when(is_callable($callback), $callback),
            'ownedWorkspaces'
        );
    }
}
