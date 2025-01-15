<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PointsRedemptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = PointsRedemption::class;

    public function definition()
    {
        return [
            'invoice_number'    => $this->faker->unique()->numerify('INV#####'),
            'category_id'       => $this->faker->numberBetween(1, 10),
            'points_redeemed'   => $this->faker->numberBetween(1, 50),
            'pointaccumulated'  => $this->faker->numberBetween(50, 500),
            'redemption_amount' => $this->faker->numberBetween(500, 5000),
            'redemption_date'   => now(),
        ];
    }
}
