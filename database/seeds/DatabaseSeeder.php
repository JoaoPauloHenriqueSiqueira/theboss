<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CompaniesTableSeeder::class);
        $this->call(CompaniesTokenTableSeeder::class);
        $this->call(UsersTablesSeeder::class);
        $this->call(ClientsTableSeeder::class);
        $this->call(ProductsTableSeeder::class);
    }
}
