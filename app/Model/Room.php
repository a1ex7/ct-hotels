<?php

    namespace App\Model;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    /**
     * @OA\Schema(
     *  schema="Room",
     *  @OA\Property(
     *      property="id",
     *      type="integer"
     *  ),
     *  @OA\Property(
     *      property="number",
     *      type="string"
     *  ),
     *  @OA\Property(
     *      property="capacity",
     *      type="integer"
     *  ),
     *  @OA\Property(
     *      property="category_id",
     *      type="integer"
     *  ),
     *  @OA\Property(
     *      property="hotel_id",
     *      type="integer"
     *  ),
     * )
     */

    /**
     * @property mixed id
     * @property mixed number
     * @property mixed capacity
     * @property mixed created_at
     * @property mixed updated_at
     */
    class Room extends Model
    {
        protected $fillable = ['number', 'capacity', 'hotel_id', 'category_id'];

        public function category(): BelongsTo
        {
            return $this->belongsTo(Category::class);
        }

        public function hotel(): BelongsTo
        {
            return $this->belongsTo(Hotel::class);
        }

        public function reservations(): HasMany
        {
            return $this->hasMany(Reservation::class);
        }
    }
