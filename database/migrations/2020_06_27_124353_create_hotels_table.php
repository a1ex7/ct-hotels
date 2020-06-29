<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateHotelsTable extends Migration
    {
        public function up(): void
        {
            Schema::create('hotels', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->tinyInteger('rating');
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('hotels');
        }
    }
