<?php

    use Illuminate\Database\Seeder;
    use App\Model\Category;

    class CategorySeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run(): void
        {
            factory(Category::class, 5)->create();
        }
    }
