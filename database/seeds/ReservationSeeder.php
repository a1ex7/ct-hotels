<?php

    use App\Model\Reservation;
    use App\Model\Room;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Seeder;

    class ReservationSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run(): void
        {
            factory(Reservation::class, 100)->state('withRoom')->create();
        }
    }
