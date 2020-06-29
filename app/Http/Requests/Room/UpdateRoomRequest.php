<?php

    namespace App\Http\Requests\Room;

    use Illuminate\Foundation\Http\FormRequest;

    /**
     * @OA\Schema(
     *     title = "Update Room request",
     *     description = "Update Room request body data",
     *     type = "object",
     *     required = {"number", "capacity", "category_id", "hotel_id"},
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
     *             example = "rooms"
     *         ),
     *         @OA\Property(
     *             property = "attributes",
     *             type = "object",
     *             @OA\Property(
     *                 property = "number",
     *                 type = "string",
     *                 description = "Room number",
     *                 example = "138B"
     *             ),
     *             @OA\Property(
     *                 property = "capacity",
     *                 type = "integer",
     *                 description = "Capacity of the room",
     *                 example = 4
     *             ),
     *             @OA\Property(
     *                 property = "category_id",
     *                 type = "integer",
     *                 description = "Room category id",
     *                 example = 1
     *             ),
     *             @OA\Property(
     *                 property = "hotel_id",
     *                 type = "integer",
     *                 description = "Room hotel id",
     *                 example = 1
     *             )
     *         )
     *     )
     * )
     */
    class UpdateRoomRequest extends FormRequest
    {
        public function rules(): array
        {
            return [
                'data'                        => 'required|array',
                'data.type'                   => 'required|in:rooms',
                'data.attributes'             => 'sometimes|array',
                'data.attributes.number'      => 'sometimes|string',
                'data.attributes.capacity'    => 'sometimes|integer|between:1,8',
                'data.attributes.hotel_id'    => 'sometimes|integer|exists:hotels,id',
                'data.attributes.category_id' => 'sometimes|integer|exists:categories,id',
            ];
        }

        public function authorize(): bool
        {
            return true;
        }
    }
