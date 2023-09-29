<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('surname');
            $table->string('last_name');
            $table->string('sexe');
            $table->string('email')->unique();
            $table->string('telephone');
            $table->string('country');
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_banned_account');
            $table->string('password');
            $table->string('member');
            $table->date('last_login')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
