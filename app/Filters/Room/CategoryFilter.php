<?php

    namespace App\Filters\Room;

    use Fouladgar\EloquentBuilder\Support\Foundation\Contracts\Filter;
    use Illuminate\Database\Eloquent\Builder;

    class CategoryFilter extends Filter
    {
        /**
         * Apply the condition to the query.
         *
         * @param Builder $builder
         * @param mixed $value
         *
         * @return Builder
         */
        public function apply(Builder $builder, $value): Builder
        {
            return $builder->whereHas('category',
                function ($query) use ($value) {
                    $query->where('id', '=', $value)->orWhere('name', '=', $value);
                });
        }
    }
