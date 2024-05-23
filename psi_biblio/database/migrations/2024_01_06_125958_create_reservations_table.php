<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration {
    public function up() {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id('reservation_id');
            $table->foreignId('book_id')->constrained('books', 'title_id'); // Poprawiona definicja klucza obcego
            $table->foreignId('member_id')->constrained('members', 'member_id');
            $table->date('reservation_date');
            $table->boolean('active');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('reservations');
    }
}
