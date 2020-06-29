<?php

    namespace App\Filters\Room;

    use Carbon\Carbon;
    use Fouladgar\EloquentBuilder\Support\Foundation\Contracts\Filter;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Support\Arr;

    class BookingFilter extends Filter
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
            $bookingStart = Carbon::parse(Arr::get($value, 'start'));
            $bookingEnd = Carbon::parse(Arr::get($value, 'end', $bookingStart));

            return $builder
                ->whereDoesntHave('reservations')
                ->OrWhereHas('reservations',
                    function ($query) use ($bookingStart, $bookingEnd) {
                        $query
                            ->where([
                                ['arrival', '>', $bookingStart],
                                ['arrival', '>', $bookingEnd],
                            ])
                            ->orWhere([
                                ['departure', '<', $bookingStart],
                                ['departure', '<', $bookingEnd],
                            ]);
                    });
        }
    }
