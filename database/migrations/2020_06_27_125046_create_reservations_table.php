<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateReservationsTable extends Migration
    {
        public function up(): void
        {
            Schema::create('reservations', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->foreignId('room_id')->constrained();
                $table->string('name');
                $table->string('phone');
                $table->tinyInteger('persons');
                $table->dateTime('arrival');
                $table->dateTime('departure');
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('reservations');
        }
    }
