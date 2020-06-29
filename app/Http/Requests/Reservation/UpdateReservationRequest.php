<?php

    namespace App\Http\Requests\Reservation;

    use Illuminate\Foundation\Http\FormRequest;

    /**
     * @OA\Schema(
     *     title = "Update Reservation request",
     *     description = "Update Reservation request body data",
     *     type = "object",
     *     required = {"name", "phone", "persons", "arrival", "departure", "room_id"},
     *     @OA\Property(
     *         property = "data",
     *         type = "object",
     *         @OA\Property(
     *             property = "id",
     *             type = "string",
     *             example = "1"
     *         ),
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
    class UpdateReservationRequest extends FormRequest
    {
        public function rules(): array
        {
            return [
                'data'                      => 'required|array',
                'data.type'                 => 'required|in:reservations',
                'data.attributes'           => 'sometimes|array',
                'data.attributes.name'      => 'sometimes|string',
                'data.attributes.phone'     => 'sometimes|string',
                'data.attributes.persons'   => 'sometimes|integer|between:1,8',
                'data.attributes.arrival'   => 'sometimes|date',
                'data.attributes.departure' => 'sometimes|date',
                'data.attributes.room_id'   => 'sometimes|integer|exists:rooms,id',
            ];
        }

        public function authorize(): bool
        {
            return true;
        }
    }
