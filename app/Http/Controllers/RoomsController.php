<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\Room\CreateRoomRequest;
    use App\Http\Requests\Room\UpdateRoomRequest;
    use App\Http\Resources\Rooms\RoomsResource;
    use App\Model\Room;
    use EloquentBuilder;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;

    class RoomsController extends Controller
    {
        /**
         * @OA\Get(
         *     path="/rooms",
         *     summary="Display a listing of the rooms",
         *     description="Returns list of all rooms",
         *     tags={"Rooms"},
         *     @OA\Parameter(
         *         name="filter[capacity]",
         *         in="query",
         *         description="Filter rooms by capacity",
         *         required=false,
         *         example="4"
         *     ),
         *     @OA\Parameter(
         *         name="filter[category]",
         *         in="query",
         *         description="Filter rooms by category",
         *         required=false,
         *         example="1"
         *     ),
         *     @OA\Parameter(
         *         name="filter[hotel]",
         *         in="query",
         *         description="Filter rooms by hotel",
         *         required=false,
         *         example="1"
         *     ),
         *     @OA\Parameter(
         *         name="filter[booking][start]",
         *         in="query",
         *         description="Start booking rooms date",
         *         required=false,
         *         example="2020-06-24"
         *     ),
         *     @OA\Parameter(
         *         name="filter[booking][end]",
         *         in="query",
         *         description="End booking rooms date",
         *         required=false,
         *         example="2020-06-30"
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Successful operation",
         *         @OA\Schema(
         *             type="array",
         *             @OA\Items(ref="#/definitions/Room")
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
            $rooms = EloquentBuilder::to(Room::class, $request->input('filter'))
                ->with(['category', 'hotel', 'reservations'])
                ->get();

            return RoomsResource::collection($rooms)->response();
        }

        /**
         * @OA\Post(
         *      path="/rooms",
         *      operationId="storeRoom",
         *      tags={"Rooms"},
         *      summary="Store new room",
         *      description="Returns room data",
         *      @OA\RequestBody(
         *          required=true,
         *          @OA\JsonContent(ref="#/components/schemas/CreateRoomRequest")
         *      ),
         *      @OA\Response(
         *          response=201,
         *          description="Successful operation",
         *          @OA\JsonContent(ref="#/components/schemas/Room")
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
         * @param CreateRoomRequest $request
         * @return JsonResponse
         */
        public function store(CreateRoomRequest $request): JsonResponse
        {
            $room = Room::create([
                'number'      => $request->input('data.attributes.number'),
                'capacity'    => $request->input('data.attributes.capacity'),
                'hotel_id'    => $request->input('data.attributes.hotel_id'),
                'category_id' => $request->input('data.attributes.category_id'),
            ]);

            return (new RoomsResource($room->loadMissing(['category', 'hotel', 'reservations'])))
                ->response()
                ->header('Location', route('rooms.show', ['room' => $room]));
        }

        /**
         * @OA\Get(
         *     path="/rooms/{room}",
         *     summary="Get room by id",
         *     tags={"Rooms"},
         *     description="Get room by id",
         *     @OA\Parameter(
         *         name="room",
         *         in="path",
         *         description="Room id",
         *         required=true,
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Display the specified resource",
         *         @OA\Schema(ref="#/definitions/Room"),
         *     ),
         *     @OA\Response(
         *         response="401",
         *         description="Unauthorized user",
         *     ),
         *     @OA\Response(
         *         response="404",
         *         description="Room is not found",
         *     )
         * )
         *
         * @param Room $room
         * @return JsonResponse
         */
        public function show(Room $room): JsonResponse
        {
            return (new RoomsResource($room->loadMissing(['category', 'hotel'])))->response();
        }

        /**
         * @OA\Put(
         *      path="/rooms/{room}",
         *      operationId="updateRoom",
         *      tags={"Rooms"},
         *      summary="Update existing room",
         *      description="Returns updated room data",
         *      @OA\Parameter(
         *          name="room",
         *          description="Room id",
         *          required=true,
         *          in="path",
         *          @OA\Schema(
         *              type="integer"
         *          )
         *      ),
         *      @OA\RequestBody(
         *          required=true,
         *          @OA\JsonContent(ref="#/components/schemas/UpdateRoomRequest")
         *      ),
         *      @OA\Response(
         *          response=202,
         *          description="Successful operation",
         *          @OA\JsonContent(ref="#/components/schemas/Room")
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
         * @param UpdateRoomRequest $request
         * @param Room $room
         * @return JsonResponse
         */
        public function update(UpdateRoomRequest $request, Room $room): JsonResponse
        {
            $room->update($request->input('data.attributes'));

            return (new RoomsResource($room))->response();
        }

        /**
         * @OA\Delete(
         *      path="/rooms/{room}",
         *      operationId="deleteRoom",
         *      tags={"Rooms"},
         *      summary="Delete existing room",
         *      description="Deletes a record and returns no content",
         *      @OA\Parameter(
         *          name="room",
         *          description="Room id",
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
         * @param Room $room
         * @return Response
         */
        public function destroy(Room $room): Response
        {
            $room->delete();

            return response(null, Response::HTTP_NO_CONTENT);
        }
    }
