<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCopiesTable extends Migration {
    public function up() {
        Schema::create('copies', function (Blueprint $table) {
            $table->string('copy_id', 25)->primary(); // Zmiana na string i ustawienie jako klucz główny
            $table->foreignId('title_id')->constrained('books', 'title_id');
            $table->boolean('available');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('copies');
    }
}


