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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('password');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->boolean('isAdmin')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
/*         if (Schema::hasTable('posts')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropForeign(['user_id']); // Drop the foreign key first
            });
        } */
/* 
        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropForeign(['user_id']); // Drop the foreign key first
            });
        }    */
       
        Schema::dropIfExists('users');
    }
};
