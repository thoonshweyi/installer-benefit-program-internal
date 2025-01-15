<?php

namespace Database\Seeders;

use App\Models\ModelHasRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'uuid' => '7fe4da95-104d-4bc5-899b-0b6f777893ec',
            'name' => 'Pro1 Admin',
            'email' => 'pro1@mail.com',
            'password' => bcrypt('pro1123'),
        ]);

        $role = Role::where('name', 'Admin')->first();

        $permissions = Permission::pluck('id', 'permission_id')->all();

        $role->syncPermissions($permissions);
     
        $user->assignRole([$role->id]);
    }
}
