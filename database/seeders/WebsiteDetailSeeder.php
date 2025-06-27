<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsiteDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('website_details')->insert([
            ['name' => 'Shopify', 'slug' => 'shopify', 'type' => 'ecommerce'],
            ['name' => 'WooCommerce', 'slug' => 'woocommerce', 'type' => 'ecommerce'],
            ['name' => 'BigCommerce', 'slug' => 'bigcommerce', 'type' => 'ecommerce'],
            ['name' => 'Magento', 'slug' => 'magento', 'type' => 'ecommerce'],
            ['name' => 'Custom Solution', 'slug' => 'custom_solution', 'type' => 'ecommerce'],
            ['name' => 'Other', 'slug' => 'other', 'type' => 'ecommerce'],
            ['name' => 'WordPress', 'slug' => 'wordpress', 'type' => 'other'],
            ['name' => 'Squarespace', 'slug' => 'squarespace', 'type' => 'other'],
            ['name' => 'Webflow', 'slug' => 'webflow', 'type' => 'other'],
            ['name' => 'Custom Developed', 'slug' => 'custom_developed', 'type' => 'other'],
            ['name' => 'Other', 'slug' => 'other', 'type' => 'other'],
        ]);
    }
}
