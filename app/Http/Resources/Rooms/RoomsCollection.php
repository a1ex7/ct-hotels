<?php

    namespace App\Http\Resources\Rooms;

    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\ResourceCollection;

    /** @see \App\Model\Room */
    class RoomsCollection extends ResourceCollection
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
