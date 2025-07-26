<?php

namespace Database\Factories;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationFactory extends Factory
{
    protected $model = Translation::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->lexify('key_??????'),
            'value' => $this->faker->sentence,
            'locale' => $this->faker->randomElement(['en', 'fr', 'es']),
            'tag' => $this->faker->randomElement(['web', 'mobile', 'desktop']),
        ];
    }
}
