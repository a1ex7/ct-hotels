<?php

    namespace App\Model;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    /**
     * @OA\Schema(
     *  schema="Hotel",
     *  @OA\Property(
     *      property="id",
     *      type="integer"
     *  ),
     *  @OA\Property(
     *      property="name",
     *      type="string"
     *  ),
     *  @OA\Property(
     *      property="rating",
     *      type="integer"
     *  )
     * )
     */

    /**
     * @property mixed name
     * @property mixed rating
     * @property mixed created_at
     * @property mixed updated_at
     */
    class Hotel extends Model
    {
        protected $fillable = ['name', 'rating'];

        public function rooms(): HasMany
        {
            return $this->hasMany(Room::class);
        }
    }
