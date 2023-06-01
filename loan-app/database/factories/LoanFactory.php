<?php

namespace Database\Factories;

use App\Constants\LoanStatus;
use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    protected $model = Loan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'amount' => $this->faker->numberBetween(10000, 90000),
            'terms' => $this->faker->numberBetween(3, 5),
            'status' => LoanStatus::PENDING,
        ];
    }
}
