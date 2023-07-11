<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('multitasks_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('multitask_id');
            $table->foreign('multitask_id')->references('id')->on('multitasks');
            
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->boolean('owner')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('multitasks_users');
    }
};
