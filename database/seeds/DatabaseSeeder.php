<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(CategorySeeder::class);
        $this->call(HotelSeeder::class);
        $this->call(ReservationSeeder::class);
    }
}
