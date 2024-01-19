<?php

namespace Database\Factories;

use App\Models\Speaker;
use App\Models\Talk;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpeakerFactory extends Factory
{
    protected $model = Speaker::class;

    public function definition(): array
    {
        $qualificationCount = $this->faker->numberBetween(0,10);
        $qualifications = $this->faker->randomElements(array_keys(Speaker::QUALIFICATIONS), $qualificationCount);
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'bio' => $this->faker->text(),
            'qualifications' => $qualifications,
            'twitter_handle' => $this->faker->word(),
        ];
    }

    public function withTalks(int $count = 1): self
    {
        return $this->has(Talk::factory()->count($count), 'talks');
    }
}
