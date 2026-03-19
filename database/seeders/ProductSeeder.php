<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 100; $i++) {
            DB::table('product')->insert([
                'category_id' => 1,
                'name' => 'HDL',
                'image_url' => 'hinh.png',
                'content' => 'Noi dung san pham',
                'price' => 198000,
                'price_discount' => 1999000,
            ]);
        }
    }
}
