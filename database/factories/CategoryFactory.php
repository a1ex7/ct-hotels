<?php

    use App\Model\Category;
    use Faker\Generator as Faker;
    use Illuminate\Database\Eloquent\Factory;

    /** @var Factory $factory */
    $factory->define(Category::class, function (Faker $faker) {
        return [
            'name' => $faker->unique()->randomElement(['Standard', 'Family', 'Residence', 'Apartment', 'Lux'])
        ];
    });
