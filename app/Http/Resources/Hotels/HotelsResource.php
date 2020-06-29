<?php

    namespace App\Http\Resources\Hotels;

    use App\Http\Resources\Rooms\RoomsCollection;
    use App\Http\Resources\Rooms\RoomsResource;
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;

    /**
     * @property mixed id
     * @property mixed name
     * @property mixed rating
     * @property mixed created_at
     * @property mixed updated_at
     */
    class HotelsResource extends JsonResource
    {
        /**
         * Transform the resource into an array.
         *
         * @param Request $request
         * @return array
         */
        public function toArray($request): array
        {
            return [
                'id'         => (int)$this->id,
                'type'       => 'hotels',
                'attributes' => [
                    'name'       => $this->name,
                    'rating'     => (int)$this->rating,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                ],
                'relationships' => [
                    'rooms' =>  RoomsCollection::collection($this->whenLoaded('rooms'))
                ]
            ];
        }
    }
