<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration {
    public function up() {
        Schema::create('loans', function (Blueprint $table) {
            $table->id('loan_id');
            $table->string('copy_id', 25)->index(); // Zmiana na string i indeksowanie
            $table->foreign('copy_id')->references('copy_id')->on('copies'); // Poprawiona definicja klucza obcego
            $table->foreignId('member_id')->constrained('members', 'member_id');
            $table->date('loan_date');
            $table->date('due_date');
            // $table->boolean('returned'); // zamiana na loan_status_id
            
            $table->foreignId('loan_status_id')->constrained('loan_status', 'loan_status_id');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('loans');
    }
}
