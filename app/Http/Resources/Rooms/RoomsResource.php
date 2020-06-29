<?php

    namespace App\Http\Resources\Rooms;

    use App\Http\Resources\Hotels\HotelsResource;
    use App\Http\Resources\Reservations\ReservationsResource;
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;

    /**
     * @property mixed id
     * @property mixed capacity
     * @property mixed created_at
     * @property mixed updated_at
     * @property mixed number
     * @property mixed hotel
     * @property mixed category
     */
    class RoomsResource extends JsonResource
    {
        /**
         * @param Request $request
         * @return array
         */
        public function toArray($request): array
        {
            return [
                'id'            => (int)$this->id,
                'type'          => 'rooms',
                'attributes'    => [
                    'category'   => $this->category,
                    'number'     => $this->number,
                    'capacity'   => (int)$this->capacity,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                ],
                'relationships' => [
                    'hotel'        => new HotelsResource($this->whenLoaded('hotel')),
                    'reservations' => ReservationsResource::collection($this->whenLoaded('reservations'))
                ]
            ];
        }
    }
