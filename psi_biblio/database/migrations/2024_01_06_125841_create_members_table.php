<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration {
    public function up() {
        Schema::create('members', function (Blueprint $table) {
            $table->id('member_id');
            $table->integer('role_id')->default(1)->constrained('roles'); // 1 = user, 2 = worker, 3 = admin
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('tel')->unique();
            $table->string('address');
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('members');
    }
}
