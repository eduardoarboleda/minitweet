<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('tweet_id')
                ->constrained()
                ->onDelete('cascade');
            $table->boolean('is_liked')->default(true); // true = liked, false = unliked
            $table->timestamps();

            $table->unique(['user_id', 'tweet_id']); // prevent multiple reacts per tweet per user
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reacts');
    }
};
