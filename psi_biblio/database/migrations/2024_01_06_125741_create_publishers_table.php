<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublishersTable extends Migration {
    public function up() {
        Schema::create('publishers', function (Blueprint $table) {
            $table->id('publisher_id');
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('publishers');
    }
}
