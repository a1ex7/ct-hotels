<?php

    namespace App\Model;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    /**
     * @OA\Schema(
     *  schema="Reservation",
     *  @OA\Property(
     *      property="id",
     *      type="integer"
     *  ),
     *  @OA\Property(
     *      property="name",
     *      type="string"
     *  ),
     *  @OA\Property(
     *      property="phone",
     *      type="string"
     *  ),
     *  @OA\Property(
     *      property="persons",
     *      type="integer"
     *  ),
     *  @OA\Property(
     *      property="arrival",
     *      type="date"
     *  ),
          *  @OA\Property(
     *      property="departure",
     *      type="date"
     *  ),
     *  @OA\Property(
     *      property="room_id",
     *      type="integer"
     *  ),
     * )
     */

    /**
     * @property mixed name
     * @property mixed phone
     * @property mixed persons
     * @property mixed arrival
     * @property mixed departure
     * @property mixed created_at
     * @property mixed updated_at
     */
    class Reservation extends Model
    {
        protected $fillable = ['name', 'phone', 'persons', 'arrival', 'departure', 'room_id'];

        protected $dates = ['arrival', 'departure'];

        public function room(): BelongsTo
        {
            return $this->belongsTo(Room::class);
        }
    }
