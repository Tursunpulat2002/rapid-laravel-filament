<?php

namespace Database\Factories;

use App\Enums\Region;
use App\Models\{Conference};
use Illuminate\Database\Eloquent\Factories\Factory;

class ConferenceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Conference::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $start_date = now()->addMonths(9);
        $end_date = now()->addMonths(9)->addDays(2);
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => $this->faker->randomElement([
                'draft',
                'published',
                'archived',
            ]),
            'region' => $this->faker->randomElement(Region::class),
            'venue_id' => null,
        ];
    }
}
