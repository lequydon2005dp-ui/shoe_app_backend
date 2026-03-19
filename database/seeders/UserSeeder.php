<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('user')->insert([
                'name' => 'ho ten',
                'email' => 'hdl' . $i . '@gmail.com',
                'phone' => '098765433' . $i,
                'password' => 'Noi dung san pham',
                'address' => 'HCM',
                'avatar' => 'user.png',
            ]);
        }
    }
}
