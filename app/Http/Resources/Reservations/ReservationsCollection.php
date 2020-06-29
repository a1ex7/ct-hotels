<?php

    namespace App\Http\Resources\Reservations;

    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\ResourceCollection;

    /** @see \App\Model\Reservation */
    class ReservationsCollection extends ResourceCollection
    {
        /**
         * @param Request $request
         * @return array
         */
        public function toArray($request): array
        {
            return [
                'data' => $this->collection,
            ];
        }
    }
