<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateRoomsTable extends Migration
    {
        public function up(): void
        {
            Schema::create('rooms', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->foreignId('hotel_id')->constrained();
                $table->foreignId('category_id')->constrained();
                $table->string('number');
                $table->integer('capacity');
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('rooms');
        }
    }
