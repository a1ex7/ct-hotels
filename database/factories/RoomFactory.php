<?php

    use App\Model\Hotel;
    use App\Model\Room;
    use Faker\Generator as Faker;
    use Illuminate\Database\Eloquent\Factory;

    /** @var Factory $factory */
    $factory->define(Room::class, function (Faker $faker) use ($factory) {
        return [
            'number'      => $faker->numberBetween(100, 900) . $faker->randomElement(['', 'A', 'B', 'C']),
            'capacity'    => $faker->numberBetween(1, 8),
            'category_id' => $faker->numberBetween(1, 5),
            'hotel_id'    => $factory->create(Hotel::class)->id
        ];
    });
