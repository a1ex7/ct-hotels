<?php

    namespace App\Http\Requests\Hotel;

    use Illuminate\Foundation\Http\FormRequest;


    /**
    * @OA\Schema(
    *     title = "Create Hotel request",
    *     description = "Create Hotel request body data",
    *     type = "object",
    *     required = {"name", "rating"},
    *     @OA\Property(
    *         property = "data",
    *         type = "object",
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
    class CreateHotelRequest extends FormRequest
    {
        public function rules(): array
        {
            return [
                'data'                   => 'required|array',
                'data.type'              => 'required|in:hotels',
                'data.attributes'        => 'required|array',
                'data.attributes.name'   => 'required|string',
                'data.attributes.rating' => 'required|integer|between:1,5',
            ];
        }

        public function authorize(): bool
        {
            return true;
        }
    }
