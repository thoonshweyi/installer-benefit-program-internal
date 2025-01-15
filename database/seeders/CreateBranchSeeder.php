<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class CreateBranchSeeder extends Seeder
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
                'branch_id' => 1,
                'branch_code' => 'MM-101',
                'branch_name_eng' => 'Lanthit',
                'branch_short_name' => 'LAN1',
                'branch_address' => 'No.76, Lanthit Street, Near Arleing Ngar Sint Pagoda, Insein Township, Yangon, Myanmar',
                'branch_phone_no' => '01-9640100, 9640110, 647730, 644832',
                'branch_active' => 1,
            ],
            [
                'branch_id' => 2,
                'branch_code' => 'MM-102',
                'branch_name_eng' => 'Theik Pan',
                'branch_short_name' => 'MDY1',
                'branch_address' => 'Ma.8/6, Theik Pan Rd, Bet: 62 && 63 St., Chanmyathazi Tsp., Mandalay, Myanmar',
                'branch_phone_no' => '02-24134, 23417, 23972, 23775, 62731',
                'branch_active' => 1,
            ],
            [
                'branch_id' => 3,
                'branch_code' => 'MM-103',
                'branch_name_eng' => 'Satsan',
                'branch_short_name' => 'SAT1',
                'branch_address' => 'No.5 Upper Pazundaung Road, Satsan, Mingalar Taung Nyunt Tsp, Yangon, Myanmar',
                'branch_phone_no' => '02-24134, 23417, 23972, 23775, 62731',
                'branch_active' => 1,
            ],
            [
                'branch_id' => 9,
                'branch_code' => 'MM-104',
                'branch_name_eng' => 'East Dagon',
                'branch_short_name' => 'EDG1',
                'branch_address' => 'No.(1/ka), No(2) Main Road, 15Qts, Near School of Nursing and Mitwifery, East Dagon Tsp, Yangon, Myanmar',
                'branch_phone_no' => '01- 2585158, 2585159',
                'branch_active' => 1,
            ],
            [
                'branch_id' => 10,
                'branch_code' => 'MM-105',
                'branch_name_eng' => 'Mawlamyine',
                'branch_short_name' => 'MLM1',
                'branch_address' => 'No.(70), Corner of Upper Main Road and A Lal Tan St, Maung Ngan Qr (Kha Pa Ya Compound), Mawlamyine',
                'branch_phone_no' => '02-233354, 23359',
                'branch_active' => 1,
            ],
            [
                'branch_id' => 11,
                'branch_code' => 'MM-106',
                'branch_name_eng' => 'Tampawady',
                'branch_short_name' => 'MDY2',
                'branch_address' => 'No.(489/490), Between Lanthit Street & Corner of Shwe San Kaing Pagoda, Inside of Kha Pa Ya, Tapawadi Quarter, Chanmyatharzi Tsp, Mandalay.',
                'branch_phone_no' => '02-56047, 56048, 56049',
                'branch_active' => 1,
            ],
            [
                'branch_id' => 19,
                'branch_code' => 'MM-107',
                'branch_name_eng' => 'Hlaing Tharyar',
                'branch_short_name' => 'HTY1',
                'branch_address' => 'No(4 to 5), Corner between Yangon-Pathein & Yangon-Ton Tay St, Infront of AGE Industrial, Inside Padan, Hlaingtharyar Tsp, Yangon.',
                'branch_phone_no' => '',
                'branch_active' => 1,
            ],
            [
                'branch_id' => 21,
                'branch_code' => 'MM-108',
                'branch_name_eng' => 'Aye Tharyar',
                'branch_short_name' => 'ATY1',
                'branch_address' => 'No.35 , 5 Quarter , Ayetharyar Township , Taunggyi Shan State',
                'branch_phone_no' => '',
                'branch_active' => 1,
            ],
            [
                'branch_id' => 27,
                'branch_code' => 'MM-112',
                'branch_name_eng' => 'PRO 1 PLUS (Terminal M)',
                'branch_short_name' => 'PTMN1',
                'branch_address' => 'No.196, 1st Floor, Terminal M Shopping Mall, No.3 Highway, Yangon Industrial Zone, Mingalardon Township, Yangon.r',
                'branch_phone_no' => '',
                'branch_active' => 1,
            ],
            [
                'branch_id' => 28,
                'branch_code' => 'MM-113',
                'branch_name_eng' => 'South Dagon',
                'branch_short_name' => 'SDG1',
                'branch_address' => 'No.523, Pin Lone Road, Corner of Mingalar Thiri street & Industrial Zone street, 23 Ward, Near of South Dagon (Ka.Nya.Na), Yangon.',
                'branch_phone_no' => '09-777047282, 09-777047283',
                'branch_active' => 1,
            ],
            [
                'branch_id' => 30,
                'branch_code' => 'MM-114',
                'branch_name_eng' => 'Shwe Pyi Thar',
                'branch_short_name' => 'SPT1',
                'branch_address' => 'No.103-104 Bayint Naung Road, Shwe Pyi Thar Industrial Zone (4)',
                'branch_phone_no' => '',
                'branch_active' => 1,
            ],
        ];
        foreach ($values as $value) {
            Branch::create($value);
         }

    }
}
