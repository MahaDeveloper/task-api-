<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $user_details = [[
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'type' => 1,  //super admin
            'password' => Hash::make('admin@1234'),
        ],
        [
            'name' => 'maha',
            'email' => 'maha@gmail.com',
            'type' => 2,  //user
            'password' => Hash::make('maha@1234'),
        ]];



        foreach ($user_details as $user_detail)
        {
            $user = new User();
            $user->name = $user_detail['name'];
            $user->email = $user_detail['email'];
            $user->type = $user_detail['type'];
            $user->password = $user_detail['password'];
            $user->save();

        }



    }
}
