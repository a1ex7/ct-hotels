<?php

    use App\Model\Reservation;
    use App\Model\Room;
    use Faker\Generator as Faker;
    use Illuminate\Database\Eloquent\Factory;

    /** @var Factory $factory */
    $factory->define(Reservation::class, function (Faker $faker) use ($factory) {
        return [
            'name'      => $faker->name(),
            'phone'     => $faker->tollFreePhoneNumber(),
            'persons'   => $faker->numberBetween(1, 8),
            'arrival'   => $faker->dateTimeBetween('-10 days', 'now'),
            'departure' => $faker->dateTimeBetween('+1 days', '+ 14 days'),
        ];
    });

    $factory->state(Reservation::class, 'withRoom', function (Faker $faker) use ($factory) {
        return [
            'room_id' => $factory->create(Room::class)->id,
        ];
});
