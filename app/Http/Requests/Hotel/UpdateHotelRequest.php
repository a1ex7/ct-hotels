<?php

    namespace App\Http\Requests\Hotel;

    use Illuminate\Foundation\Http\FormRequest;

    /**
     * @OA\Schema(
     *     title = "Update Hotel request",
     *     description = "Update Hotel request body data",
     *     type = "object",
     *     required = {"name", "rating"},
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
     *             example = "hotels"
     *         ),
     *         @OA\Property(
     *             property = "attributes",
     *             type = "object",
     *             @OA\Property(
     *                 property = "name",
     *                 type = "string",
     *                 description = "Name of the hotel",
     *                 example = "Some SPA Resort"
     *             ),
     *             @OA\Property(
     *                 property = "rating",
     *                 type = "integer",
     *                 description = "Rating of the hotel",
     *                 example = 5
     *             )
     *         )
     *     )
     * )
     */
    class UpdateHotelRequest extends FormRequest
    {
        public function rules(): array
        {
            return [
                'data'                   => 'required|array',
                'data.type'              => 'required|in:hotels',
                'data.attributes'        => 'sometimes|required|array',
                'data.attributes.name'   => 'sometimes|required|string',
                'data.attributes.rating' => 'sometimes|required|integer|between:1,5',
            ];
        }

        public function authorize(): bool
        {
            return true;
        }
    }
