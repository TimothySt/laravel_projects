<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksAuthorsTable extends Migration {
    public function up() {
        Schema::create('books_authors', function (Blueprint $table) {
            $table->foreignId('title_id')->constrained('books', 'title_id');
            $table->foreignId('author_id')->constrained('authors', 'author_id'); // Poprawiona definicja klucza obcego
            $table->unique(['title_id', 'author_id']);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('books_authors');
    }
}
