<?php

    namespace App\Http\Resources\Reservations;

    use App\Http\Resources\Rooms\RoomsResource;
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;

    /**
     * @property mixed id
     * @property mixed name
     * @property mixed persons
     * @property mixed phone
     * @property mixed arrival
     * @property mixed departure
     * @property mixed created_at
     * @property mixed updated_at
     * @property mixed room_id
     */
    class ReservationsResource extends JsonResource
    {
        /**
         * @param Request $request
         * @return array
         */
        public function toArray($request): array
        {
            return [
                'id'            => (int)$this->id,
                'type'          => 'reservations',
                'attributes'    => [
                    'room_id'    => $this->room_id,
                    'name'       => $this->name,
                    'phone'      => $this->phone,
                    'persons'    => (int)$this->persons,
                    'arrival'    => $this->arrival,
                    'departure'  => $this->departure,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                ],
                'relationships' => [
                    'room' => new RoomsResource($this->whenLoaded('room'))
                ]
            ];
        }
    }
