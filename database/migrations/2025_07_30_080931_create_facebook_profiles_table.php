<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('facebook_profiles', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('facebook_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_profiles');
    }
};
