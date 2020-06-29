<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\Hotel\CreateHotelRequest;
    use App\Http\Requests\Hotel\UpdateHotelRequest;
    use App\Http\Resources\Hotels\HotelsResource;
    use App\Model\Hotel;
    use EloquentBuilder;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;

    class HotelsController extends Controller
    {
        /**
         * @OA\Get(
         *     path="/hotels",
         *     summary="Display a listing of the hotels",
         *     description="Returns list of all hotels",
         *     tags={"Hotels"},
         *     @OA\Parameter(
         *         name="filter[rating]",
         *         in="query",
         *         description="Filter hotels by rating",
         *         required=false,
         *         example="5"
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Successful operation",
         *         @OA\Schema(
         *             type="array",
         *             @OA\Items(ref="#/definitions/Hotel")
         *         ),
         *     ),
         *     @OA\Response(
         *         response="401",
         *         description="Unauthorized",
         *     ),
         * )
         *
         * @param Request $request
         * @return JsonResponse
         */

        public function index(Request $request): JsonResponse
        {

            $hotels = EloquentBuilder::to(Hotel::class, $request->input('filter'))->paginate();

            return HotelsResource::collection($hotels)->response();
        }

        /**
         * @OA\Post(
         *      path="/hotels",
         *      operationId="storeHotel",
         *      tags={"Hotels"},
         *      summary="Store new hotel",
         *      description="Returns hotel data",
         *      @OA\RequestBody(
         *          required=true,
         *          @OA\JsonContent(ref="#/components/schemas/CreateHotelRequest")
         *      ),
         *      @OA\Response(
         *          response=201,
         *          description="Successful operation",
         *          @OA\JsonContent(ref="#/components/schemas/Hotel")
         *       ),
         *      @OA\Response(
         *          response=400,
         *          description="Bad Request"
         *      ),
         *      @OA\Response(
         *          response=401,
         *          description="Unauthenticated",
         *      ),
         *      @OA\Response(
         *          response=403,
         *          description="Forbidden"
         *      )
         * )
         *
         * @param CreateHotelRequest $request
         * @return JsonResponse
         */
        public function store(CreateHotelRequest $request): JsonResponse
        {
            $hotel = Hotel::create([
                'name'   => $request->input('data.attributes.name'),
                'rating' => $request->input('data.attributes.rating'),
            ]);

            return (new HotelsResource($hotel))
                ->response()
                ->header('Location', route('hotels.show', ['hotel' => $hotel]));
        }

        /**
         * @OA\Get(
         *     path="/hotels/{hotel}",
         *     summary="Get hotel by id",
         *     tags={"Hotels"},
         *     description="Get hotel by id",
         *     @OA\Parameter(
         *         name="hotel",
         *         in="path",
         *         description="Hotel id",
         *         required=true,
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Display the specified resource",
         *         @OA\Schema(ref="#/definitions/Hotel"),
         *     ),
         *     @OA\Response(
         *         response="401",
         *         description="Unauthorized user",
         *     ),
         *     @OA\Response(
         *         response="404",
         *         description="Hotel is not found",
         *     )
         * )
         *
         * @param Hotel $hotel
         * @return HotelsResource
         */
        public function show(Hotel $hotel): HotelsResource
        {
            return new HotelsResource($hotel);
        }

        /**
         * @OA\Put(
         *      path="/hotels/{hotel}",
         *      operationId="updateHotel",
         *      tags={"Hotels"},
         *      summary="Update existing hotel",
         *      description="Returns updated hotel data",
         *      @OA\Parameter(
         *          name="hotel",
         *          description="Hotel id",
         *          required=true,
         *          in="path",
         *          @OA\Schema(
         *              type="integer"
         *          )
         *      ),
         *      @OA\RequestBody(
         *          required=true,
         *          @OA\JsonContent(ref="#/components/schemas/UpdateHotelRequest")
         *      ),
         *      @OA\Response(
         *          response=202,
         *          description="Successful operation",
         *          @OA\JsonContent(ref="#/components/schemas/Hotel")
         *       ),
         *      @OA\Response(
         *          response=400,
         *          description="Bad Request"
         *      ),
         *      @OA\Response(
         *          response=401,
         *          description="Unauthenticated",
         *      ),
         *      @OA\Response(
         *          response=403,
         *          description="Forbidden"
         *      ),
         *      @OA\Response(
         *          response=404,
         *          description="Resource Not Found"
         *      )
         * )
         *
         * @param UpdateHotelRequest $request
         * @param Hotel $hotel
         * @return JsonResponse
         */
        public function update(UpdateHotelRequest $request, Hotel $hotel): JsonResponse
        {
            $hotel->update($request->input('data.attributes'));

            return (new HotelsResource($hotel))->response();
        }

        /**
         * @OA\Delete(
         *      path="/hotels/{hotel}",
         *      operationId="deleteHotel",
         *      tags={"Hotels"},
         *      summary="Delete existing hotel",
         *      description="Deletes a record and returns no content",
         *      @OA\Parameter(
         *          name="hotel",
         *          description="Hotel id",
         *          required=true,
         *          in="path",
         *          @OA\Schema(
         *              type="integer"
         *          )
         *      ),
         *      @OA\Response(
         *          response=204,
         *          description="Successful operation",
         *          @OA\JsonContent()
         *       ),
         *      @OA\Response(
         *          response=401,
         *          description="Unauthenticated",
         *      ),
         *      @OA\Response(
         *          response=403,
         *          description="Forbidden"
         *      ),
         *      @OA\Response(
         *          response=404,
         *          description="Resource Not Found"
         *      )
         * )
         *
         * @param Hotel $hotel
         * @return Response
         */
        public function destroy(Hotel $hotel): Response
        {
            $hotel->delete();

            return response(null, Response::HTTP_NO_CONTENT);
        }
    }
