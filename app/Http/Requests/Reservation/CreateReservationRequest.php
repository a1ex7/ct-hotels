<?php

    namespace App\Http\Requests\Reservation;

    use Illuminate\Foundation\Http\FormRequest;

    /**
     * @OA\Schema(
     *     title = "Create Reservation request",
     *     description = "Create Reservation request body data",
     *     type = "object",
     *     required = {"name", "phone", "persons", "arrival", "departure", "room_id"},
     *     @OA\Property(
     *         property = "data",
     *         type = "object",
     *         @OA\Property(
     *             property = "type",
     *             type = "string",
     *             example = "reservations"
     *         ),
     *         @OA\Property(
     *             property = "attributes",
     *             type = "object",
     *             @OA\Property(
     *                 property = "name",
     *                 type = "string",
     *                 description = "Client's name",
     *                 example = "Alex Core"
     *             ),
     *             @OA\Property(
     *                 property = "phone",
     *                 type = "string",
     *                 description = "Client's phone",
     *                 example = "(050) 123-45-67"
     *             ),
     *             @OA\Property(
     *                 property = "persons",
     *                 type = "integer",
     *                 description = "Number of peoples",
     *                 example = 4
     *             ),
     *             @OA\Property(
     *                 property = "arrival",
     *                 type = "integer",
     *                 description = "Clietn arrival date",
     *                 example = "2020-07-06 12:00"
     *             ),
     *             @OA\Property(
     *                 property = "departure",
     *                 type = "integer",
     *                 description = "Clietn departure date",
     *                 example = "2020-07-12 14:00"
     *             ),
     *             @OA\Property(
     *                 property = "room_id",
     *                 type = "integer",
     *                 description = "Reservation room id",
     *                 example = 1
     *             )
     *         )
     *     )
     * )
     */
    class CreateReservationRequest extends FormRequest
    {
        public function rules(): array
        {
            return [
                'data'                      => 'required|array',
                'data.type'                 => 'required|in:reservations',
                'data.attributes'           => 'required|array',
                'data.attributes.name'      => 'required|string',
                'data.attributes.phone'     => 'required|string',
                'data.attributes.persons'   => 'required|integer|between:1,8',
                'data.attributes.arrival'   => 'required|date',
                'data.attributes.departure' => 'required|date',
                'data.attributes.room_id'   => 'required|integer|exists:rooms,id',
            ];
        }

        public function authorize(): bool
        {
            return true;
        }
    }
