<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@admin.com',
            'password' => '123456',
            'email_verified_at' =>  currentDateTimeInsert(),

        ]);

        $user->assignRole(Role::where('name', 'Super Admin')->first());


        $user = User::create([
            'name' => 'Account Officer',
            'email' => 'account@test.com',
            'password' => '123456',
            'email_verified_at' =>  currentDateTimeInsert(),
        ]);

        $user->assignRole(Role::where('name', 'Account')->first());


        $user = User::create([
            'name' => 'Water Billing Officer',
            'email' => 'water@test.com',
            'password' => '123456',
            'email_verified_at' =>  currentDateTimeInsert(),
        ]);

        $user->assignRole(Role::where('name', 'Water')->first());



        $user = User::create([
            'name' => 'Housing Officer',
            'email' => 'housing@test.com',
            'password' => '123456',
            'email_verified_at' =>  currentDateTimeInsert(),
        ]);

        $user->assignRole(Role::where('name', 'Housing')->first());


    }
}
