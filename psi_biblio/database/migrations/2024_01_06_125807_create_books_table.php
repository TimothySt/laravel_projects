<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration {
    public function up() {
        Schema::create('books', function (Blueprint $table) {
            $table->id('title_id');
            $table->string('isbn')->unique(); // Dodaj unikalność dla pola ISBN
            $table->string('title');
            $table->text('description');
            $table->date('published_date');
            
            // Zmieniono 'id' na 'publisher_id' w poniższej linii
            $table->foreignId('publisher_id')->constrained('publishers', 'publisher_id');
    
            $table->integer('pages')->unsigned(); // Dodaj unsigned dla liczby stron
            $table->string('language');
            $table->timestamps();
        });
    }
    

    public function down() {
        Schema::dropIfExists('books');
    }
}
