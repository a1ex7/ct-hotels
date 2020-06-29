<?php

    namespace App\Model;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class Category extends Model
    {
        public $timestamps = false;

        public function rooms(): HasMany
        {
            return $this->hasMany(Room::class);
        }
    }
