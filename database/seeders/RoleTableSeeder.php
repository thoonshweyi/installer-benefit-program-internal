<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleTableSeeder extends Seeder
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
                'name' => 'Admin',
                'guard_name' => 'web'
            ],
            [
                'name' => 'Super Admin',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Marketing',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Branch Manager',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Cashier',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Finance',
                'guard_name' => 'web',
            ],
        ];
        foreach ($values as $value) {
            $role =  Role::firstOrCreate($value);
            if($value['name']=='Admin'){
                $permissions = Permission::wherein('name',['view-dashboard','view-promotion','create-promotion','edit-promotion','delete-promotion','approve-promotion','reject-promotion','view-ticket','create-ticket','print-ticket','reprint-ticket','view-customer','create-user','edit-user','view-user','delete-user','update-profile','view-report','export-report'])->get()->pluck('id');
            }
            if($value['name']=='Super Admin'){
                $permissions = Permission::wherein('name',['view-dashboard','view-promotion','create-promotion','edit-promotion','delete-promotion','approve-promotion','reject-promotion','view-ticket','create-ticket','print-ticket','reprint-ticket','view-customer','create-user','edit-user','view-user','delete-user','update-profile','view-role','create-role','edit-role','delete-role','view-report','export-report'])->get()->pluck('id');
            }
            if($value['name']=='Marketing'){
                $permissions = Permission::wherein('name',['view-dashboard','view-promotion','create-promotion','edit-promotion','delete-promotion','view-ticket','view-customer','update-profile','view-report','export-report'])->get()->pluck('id');
            }
            if($value['name']=='Branch Manager'){
                $permissions = Permission::wherein('name',['view-dashboard','view-promotion','view-ticket','print-ticket','reprint-ticket','delete-ticket','update-profile','view-report'])->get()->pluck('id');
            }
            if($value['name']=='Cashier'){
                $permissions = Permission::wherein('name',['view-dashboard','view-promotion','view-ticket','create-ticket','print-ticket','update-profile'])->get()->pluck('id');
               
            }
            if($value['name']=='Finance'){
                $permissions = Permission::wherein('name',['view-dashboard','view-promotion','view-ticket','update-profile','view-report','export-report'])->get()->pluck('id');

            }

            $role->syncPermissions($permissions);
        }
       
    }
}
