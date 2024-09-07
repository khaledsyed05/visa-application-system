<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\User;
use App\Models\Destination;
use App\Models\VisaType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'destination_id' => Destination::factory(),
            'visa_type_id' => VisaType::factory(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'applicant_data' => [
                'name' => $this->faker->name,
                'age' => $this->faker->numberBetween(18, 80),
            ],
            'tracking_number' => $this->faker->unique()->uuid,
        ];
    }
}