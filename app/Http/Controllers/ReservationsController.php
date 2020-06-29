<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\Reservation\CreateReservationRequest;
    use App\Http\Requests\Reservation\UpdateReservationRequest;
    use App\Http\Resources\Reservations\ReservationsResource;
    use App\Model\Reservation;
    use EloquentBuilder;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;

    class ReservationsController extends Controller
    {
        /**
         * @OA\Get(
         *     path="/reservations",
         *     summary="Display a listing of the reservations",
         *     description="Returns list of all reservations",
         *     tags={"Reservations"},
         *     @OA\Response(
         *         response=200,
         *         description="Successful operation",
         *         @OA\Schema(
         *             type="array",
         *             @OA\Items(ref="#/definitions/Reservation")
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
            $reservations = EloquentBuilder::to(Reservation::class, $request->input('filter'))
                ->with(['room.hotel'])
                ->paginate();

            return ReservationsResource::collection($reservations)->response();
        }

        /**
         * @OA\Post(
         *      path="/reservations",
         *      operationId="storeReservation",
         *      tags={"Reservations"},
         *      summary="Store new reservation",
         *      description="Returns reservation data",
         *      @OA\RequestBody(
         *          required=true,
         *          @OA\JsonContent(ref="#/components/schemas/CreateReservationRequest")
         *      ),
         *      @OA\Response(
         *          response=201,
         *          description="Successful operation",
         *          @OA\JsonContent(ref="#/components/schemas/Reservation")
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
         * @param CreateReservationRequest $request
         * @return JsonResponse
         */
        public function store(CreateReservationRequest $request): JsonResponse
        {
            $reservation = Reservation::create([
                'name'      => $request->input('data.attributes.name'),
                'phone'     => $request->input('data.attributes.phone'),
                'persons'   => $request->input('data.attributes.persons'),
                'arrival'   => $request->input('data.attributes.arrival'),
                'departure' => $request->input('data.attributes.departure'),
                'room_id'   => $request->input('data.attributes.room_id'),
            ]);

            return (new ReservationsResource($reservation->loadMissing('room.hotel')))
                ->response()
                ->header('Location', route('reservations.show', ['reservation' => $reservation]));
        }

        /**
         * @OA\Get(
         *     path="/reservations/{reservation}",
         *     summary="Get reservation by id",
         *     tags={"Reservations"},
         *     description="Get reservation by id",
         *     @OA\Parameter(
         *         name="reservation",
         *         in="path",
         *         description="Reservation id",
         *         example="1",
         *         required=true,
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Display the specified resource",
         *         @OA\Schema(ref="#/definitions/Reservation"),
         *     ),
         *     @OA\Response(
         *         response="401",
         *         description="Unauthorized user",
         *     ),
         *     @OA\Response(
         *         response="404",
         *         description="Reservation is not found",
         *     )
         * )
         *
         * @param Reservation $reservation
         * @return JsonResponse
         */
        public function show(Reservation $reservation): JsonResponse
        {
            return (new ReservationsResource($reservation->loadMissing('room.hotel')))->response();
        }

        /**
         * @OA\Put(
         *      path="/reservations/{reservation}",
         *      operationId="updateReservation",
         *      tags={"Reservations"},
         *      summary="Update existing reservation",
         *      description="Returns updated reservation data",
         *      @OA\Parameter(
         *          name="reservation",
         *          description="Reservation id",
         *          example="1",
         *          required=true,
         *          in="path",
         *          @OA\Schema(
         *              type="integer"
         *          )
         *      ),
         *      @OA\RequestBody(
         *          required=true,
         *          @OA\JsonContent(ref="#/components/schemas/UpdateReservationRequest")
         *      ),
         *      @OA\Response(
         *          response=202,
         *          description="Successful operation",
         *          @OA\JsonContent(ref="#/components/schemas/Reservation")
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
         * @param UpdateReservationRequest $request
         * @param Reservation $reservation
         * @return JsonResponse
         */
        public function update(UpdateReservationRequest $request, Reservation $reservation): JsonResponse
        {
            $reservation->update($request->input('data.attributes'));

            return (new ReservationsResource($reservation))->response();
        }

        /**
         * @OA\Delete(
         *      path="/reservations/{reservation}",
         *      operationId="deleteReservation",
         *      tags={"Reservations"},
         *      summary="Delete existing reservation",
         *      description="Deletes a record and returns no content",
         *      @OA\Parameter(
         *          name="reservation",
         *          description="Reservation id",
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
         * @param Reservation $reservation
         * @return Response
         */
        public function destroy(Reservation $reservation): Response
        {
            $reservation->delete();

            return response(null, Response::HTTP_NO_CONTENT);
        }
    }
