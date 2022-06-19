<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Depo;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Point;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Depo>
 */
class DepoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Depo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create(['role' => 'Admin'])->id,
            'nama' => $this->faker->word,
            'alamat' => $this->faker->streetName(),
            'lokasi' => new Point($this->faker->randomFloat(2, -90, 90), $this->faker->randomFloat(2, -90, 90)),
        ];
    }
}
