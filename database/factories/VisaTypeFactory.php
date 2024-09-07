<?php

namespace Database\Factories;

use App\Models\VisaType;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisaTypeFactory extends Factory
{
    protected $model = VisaType::class;

    protected static $visaTypes = ['Tourist', 'Business', 'Student', 'Work', 'Transit', 'Diplomatic', 'Medical', 'Family', 'Retirement', 'Working Holiday'];

    public function definition()
    {
        static $index = 0;

        return [
            'name' => static::$visaTypes[$index++ % count(static::$visaTypes)],
            'description' => $this->faker->sentence,
        ];
    }
}