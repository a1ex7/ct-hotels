<?php

    namespace App\Http\Requests\Room;

    use Illuminate\Foundation\Http\FormRequest;

    /**
     * @OA\Schema(
     *     title = "Create Room request",
     *     description = "Create Room request body data",
     *     type = "object",
     *     required = {"number", "capacity", "category_id", "hotel_id"},
     *     @OA\Property(
     *         property = "data",
     *         type = "object",
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
    class CreateRoomRequest extends FormRequest
    {
        public function rules(): array
        {
            return [
                'data'                        => 'required|array',
                'data.type'                   => 'required|in:rooms',
                'data.attributes'             => 'required|array',
                'data.attributes.number'      => 'required|string',
                'data.attributes.capacity'    => 'required|integer|between:1,8',
                'data.attributes.hotel_id'    => 'required|integer|exists:hotels,id',
                'data.attributes.category_id' => 'required|integer|exists:categories,id',
            ];
        }

        public function authorize(): bool
        {
            return true;
        }
    }
