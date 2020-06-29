<?php

    use App\Model\Hotel;
    use App\Model\Room;
    use Illuminate\Database\Seeder;

    class HotelSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run(): void
        {
            factory(Hotel::class, 5)
                ->create()
                ->each(function ($hotel) {
                    $rooms = factory(Room::class, 10)->make();
                    $hotel->rooms()->saveMany($rooms);
                });
        }
    }
