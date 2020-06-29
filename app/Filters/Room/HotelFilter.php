<?php

    namespace App\Filters\Room;

    use Fouladgar\EloquentBuilder\Support\Foundation\Contracts\Filter;
    use Illuminate\Database\Eloquent\Builder;

    class HotelFilter extends Filter
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
            return $builder->whereHas('hotel',
                function ($query) use ($value) {
                    $query->where('id', '=', $value);
                });
        }
    }
