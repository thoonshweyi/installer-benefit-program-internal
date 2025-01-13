<?php

namespace Database\Seeders;

use App\Models\LuckyDrawType;
use Illuminate\Database\Seeder;

class PromotionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values = [
            [
                'uuid' => '310ea3a5-2af6-40e6-96ba-d1d90e12d48d',
                'name' => 'Main Promotion',
                'description' => 'Main Promotion',
                'status' => '1',
            ],
            [
                'uuid' => '079b1c0e-2a01-48e5-9e21-4d808d932d8a',
                'name' => 'Category Promotion',
                'description' => 'Category Promotion',
                'status' => '1',
            ],
            [
                'uuid' => 'cfc9b484-e195-4f64-ae94-e005a0c293a4',
                'name' => 'Event Promotion',
                'description' => 'Event Promotion',
                'status' => '1',
            ],
        ];
        foreach ($values as $value) {
            LuckyDrawType::create($value);
         }
    }
}
