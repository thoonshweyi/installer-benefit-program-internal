<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CreateCategorySeeder extends Seeder
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
                'name' => '01-Cement and Block',
                'short_name' => '01-Cement and Block',
                'erp_category_id' => '1',
            ],
            [
                'name' => '04-Sanitary Ware',
                'short_name' => '04-Sanitary Ware',
                'erp_category_id' => '13',
            ],
            [
                'name' => '05-Garden and Accessories',
                'short_name' => '05-Garden and Accessories',
                'erp_category_id' => '5',
            ],
            [
                'name' => '06-Hardware and Tools',
                'short_name' => '06-Hardware and Tools',
                'erp_category_id' => '7',
            ],
            [
                'name' => '07-Surface Covering',
                'short_name' => '07-Surface Covering',
                'erp_category_id' => '14',
            ],
            [
                'name' => '08-Door/Window/Wood',
                'short_name' => '08-Door/Window/Wood',
                'erp_category_id' => '8',
            ],
            [
                'name' => '09-Electrical and Accessories',
                'short_name' => '09-Electrical and Accessories',
                'erp_category_id' => '9',
            ],
            [
                'name' => '10-Home Appliance ',
                'short_name' => '10-Home Appliance ',
                'erp_category_id' => '10',
            ],
            [
                'name' => '11-Paint and Chemical',
                'short_name' => '11-Paint and Chemical',
                'erp_category_id' => '16',
            ],
            [
                'name' => '12-Houseware and Kitchen',
                'short_name' => '12-Houseware and Kitchen',
                'erp_category_id' => '19',
            ],
            [
                'name' => '13-Furniture and Bedding',
                'short_name' => '13-Furniture and Bedding',
                'erp_category_id' => '15',
            ],
            [
                'name' => '14-Stationery & Digital Equipment',
                'short_name' => '14-Stationery & Digital Equipment',
                'erp_category_id' => '25',
            ],
            [
                'name' => '06-Hardware and Tools',
                'short_name' => '06-Hardware and Tools',
                'erp_category_id' => '6',
            ],
            [
                'name' => '13-Furniture and Bedding',
                'short_name' => '13-Furniture and Bedding',
                'erp_category_id' => '20',
            ],
            [
                'name' => '12-Houseware and Kitchen',
                'short_name' => '12-Houseware and Kitchen',
                'erp_category_id' => '11',
            ],
            [
                'name' => '12-Houseware and Kitchen',
                'short_name' => '12-Houseware and Kitchen',
                'erp_category_id' => '21',
            ],
            [
                'name' => '12-Houseware and Kitchen',
                'short_name' => '12-Houseware and Kitchen',
                'erp_category_id' => '22',
            ],
            [
                'name' => '12-Houseware and Kitchen',
                'short_name' => '12-Houseware and Kitchen',
                'erp_category_id' => '23',
            ],
            [
                'name' => '12-Houseware and Kitchen',
                'short_name' => '12-Houseware and Kitchen',
                'erp_category_id' => '12',
            ],
            [
                'name' => '11-Paint and Chemical',
                'short_name' => '11-Paint and Chemical',
                'erp_category_id' => '18',
            ],
            [
                'name' => '05-Garden and Accessories',
                'short_name' => '05-Garden and Accessories',
                'erp_category_id' => '4',
            ],
            [
                'name' => '11-Paint and Chemical',
                'short_name' => '11-Paint and Chemical',
                'erp_category_id' => '17',
            ],
        ];
        foreach ($values as $value) {
            Category::create($value);
         }
       
    }
}
