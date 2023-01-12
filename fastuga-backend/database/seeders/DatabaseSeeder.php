<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public static $seedType = "small";
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("-----------------------------------------------");
        $this->command->info("START of database seeder");
        $this->command->info("-----------------------------------------------");

        DatabaseSeeder::$seedType = $this->command->choice('What is the size of seed data (choose "full" for publishing)?', ['small', 'full'], 0);

        DB::statement("SET foreign_key_checks=0");

        DB::table('users')->delete();
        DB::table('customers')->delete();
        DB::table('products')->delete();
        DB::table('order_items')->delete();
        DB::table('orders')->delete();

        DB::statement('ALTER TABLE users AUTO_INCREMENT = 0');
        DB::statement('ALTER TABLE products AUTO_INCREMENT = 0');
        DB::statement('ALTER TABLE order_items AUTO_INCREMENT = 0');
        DB::statement('ALTER TABLE orders AUTO_INCREMENT = 0');

        DB::statement("SET foreign_key_checks=1");


        $this->call(ProductsSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(OrdersSeeder::class);

        $this->command->info("-----------------------------------------------");
        $this->command->info("END of database seeder");
        $this->command->info("-----------------------------------------------");
    }
}
