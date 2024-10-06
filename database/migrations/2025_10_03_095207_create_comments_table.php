<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //Pivot table between many-to-many relationship user and post
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('comment');
            $table->foreignId('post_id')->constrained()
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('user_id')->constrained()
            ->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
