<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AccessControlSeeder::class,
            StorageProviderSeeder::class,
            AdminUserSeeder::class,
        ]);

        if (is_file(base_path('store_categories_mysql.sql'))) {
            $this->call(StoreCategoriesSqlSeeder::class);
        }
    }
}
