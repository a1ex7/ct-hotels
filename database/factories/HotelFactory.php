<?php

    use App\Model\Hotel;
    use Faker\Generator as Faker;
    use Illuminate\Database\Eloquent\Factory;

    /** @var Factory $factory */
    $factory->define(Hotel::class, function (Faker $faker) {
        return [
            'name'   => $faker->sentence(2),
            'rating' => $faker->numberBetween(1, 5)
        ];
    });
