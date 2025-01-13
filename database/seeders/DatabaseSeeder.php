<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\NRCNameSeeder;
use Database\Seeders\NRCNaingSeeder;
use Database\Seeders\NRCNumberSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(PermissionTableSeeder::class);
        // $this->call(RoleTableSeeder::class);
        // $this->call(NRCNumberSeeder::class);
        // $this->call(NRCNameSeeder::class);
        // $this->call(NRCNaingSeeder::class);
        // $this->call(CreateAdminUserSeeder::class);
        // $this->call(CreateCategorySeeder::class);
        // $this->call(CreateBranchSeeder::class);
        $this->call(PromotionTypeSeeder::class);

    }
}
