<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $userIds = User::pluck('id');

        return [
            'user_id'  => $this->faker->randomElement($userIds),
            'category' => $this->faker->randomElement(['Fundamentals', 'String', 'Algorithms', 'Mathematic', 'Performance', 'Booleans', 'Functions']),
            'title'    => $this->faker->text(100),
            'body'     => $this->faker->text(350),
            'solved'   => 0
        ];
    }
}
