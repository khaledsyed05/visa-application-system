<?php

namespace Database\Factories;

use App\Models\Destination;
use Illuminate\Database\Eloquent\Factories\Factory;

class DestinationFactory extends Factory
{
    protected $model = Destination::class;

    // List of countries and their codes
    protected static $countries = [
        'United States' => 'US', 'Canada' => 'CA', 'United Kingdom' => 'UK', 'France' => 'FR',
        'Germany' => 'DE', 'Italy' => 'IT', 'Spain' => 'ES', 'Japan' => 'JP', 'Australia' => 'AU',
        'Brazil' => 'BR', 'India' => 'IN', 'China' => 'CN', 'Russia' => 'RU', 'South Africa' => 'ZA',
        'Mexico' => 'MX', 'Argentina' => 'AR', 'Netherlands' => 'NL', 'Switzerland' => 'CH',
        'Sweden' => 'SE', 'Norway' => 'NO'
    ];

    public function definition()
    {
        static $index = 0;
        $countries = array_keys(static::$countries);
        $codes = array_values(static::$countries);

        if ($index >= count(static::$countries)) {
            throw new \RuntimeException('Ran out of unique country-code pairs.');
        }

        $country = $countries[$index];
        $code = $codes[$index];
        $index++;

        return [
            'name' => $country,
            'code' => $code,
            // Add other fields as necessary
        ];
    }
}