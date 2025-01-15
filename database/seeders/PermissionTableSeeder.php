<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ["permission_id" => 1, "name" => "view-dashboard", "guard_name" => "web", "group_name" => "promotion"],
            ["permission_id" => 2, "name" => "view-promotion", "guard_name" => "web", "group_name" => "promotion"],
            ["permission_id" => 3, "name" => "create-promotion", "guard_name" => "web", "group_name" => "promotion"],
            ["permission_id" => 4, "name" => "edit-promotion", "guard_name" => "web", "group_name" => "promotion"],
            ["permission_id" => 5, "name" => "delete-promotion", "guard_name" => "web", "group_name" => "promotion"],
            ["permission_id" => 6, "name" => "approve-promotion", "guard_name" => "web", "group_name" => "promotion"],
            ["permission_id" => 7, "name" => "reject-promotion", "guard_name" => "web", "group_name" => "promotion"],
            ["permission_id" => 8, "name" => "view-ticket", "guard_name" => "web", "group_name" => "ticket"],
            ["permission_id" => 9, "name" => "create-ticket", "guard_name" => "web", "group_name" => "ticket"],
            ["permission_id" => 10, "name" => "print-ticket", "guard_name" => "web", "group_name" => "ticket"],
            ["permission_id" => 11, "name" => "reprint-ticket", "guard_name" => "web", "group_name" => "ticket"],
            ["permission_id" => 12, "name" => "delete-ticket", "guard_name" => "web", "group_name" => "ticket"],
            ["permission_id" => 13, "name" => "export-not-use-tickets", "guard_name" => "web", "group_name" => "ticket"],
            ["permission_id" => 14, "name" => "view-customer", "guard_name" => "web", "group_name" => "customer"],
            ["permission_id" => 15, "name" => "find-customer", "guard_name" => "web", "group_name" => "customer"],
            ["permission_id" => 16, "name" => "create-user", "guard_name" => "web", "group_name" => "user"],
            ["permission_id" => 17, "name" => "edit-user", "guard_name" => "web", "group_name" => "user"],
            ["permission_id" => 18, "name" => "view-user", "guard_name" => "web", "group_name" => "user"],
            ["permission_id" => 19, "name" => "delete-user", "guard_name" => "web", "group_name" => "user"],
            ["permission_id" => 20, "name" => "update-profile", "guard_name" => "web", "group_name" => "user"],
            ["permission_id" => 21, "name" => "view-role", "guard_name" => "web", "group_name" => "role"],
            ["permission_id" => 22, "name" => "create-role", "guard_name" => "web", "group_name" => "role"],
            ["permission_id" => 23, "name" => "edit-role", "guard_name" => "web", "group_name" => "role"],
            ["permission_id" => 24, "name" => "delete-role", "guard_name" => "web", "group_name" => "role"],
            ["permission_id" => 25, "name" => "view-report", "guard_name" => "web", "group_name" => "report"],
            ["permission_id" => 26, "name" => "export-report", "guard_name" => "web", "group_name" => "report"],
            ["permission_id" => 27, "name" => "view-branch", "guard_name" => "web", "group_name" => "branch"],
            ["permission_id" => 28, "name" => "create-promotion-type", "guard_name" => "web", "group_name" => "promotion"],
            ["permission_id" => 29, "name" => "view-promotion-type", "guard_name" => "web", "group_name" => "promotion"],
            ["permission_id" => 30, "name" => "create-main-promotion", "guard_name" => "web", "group_name" => "promotion"],
            ["permission_id" => 31, "name" => "create-normal-promotion", "guard_name" => "web", "group_name" => "promotion"],
            ["permission_id" => 32, "name" => "add-reword", "guard_name" => "web", "group_name" => "promotion"],
            ["permission_id" => 33, "name" => "extend-reword", "guard_name" => "web", "group_name" => "promotion"],
            ["permission_id" => 34, "name" => "edit-percentage", "guard_name" => "web", "group_name" => "promotion"],
            ["permission_id" => 35, "name" => "active-inactive-promotion", "guard_name" => "web", "group_name" => "promotion"],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
