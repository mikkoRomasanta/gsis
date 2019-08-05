<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(uomSeeder::class);
        $this->call(areaSeeder::class);
        $this->call(userSeeder::class);
    }
}
