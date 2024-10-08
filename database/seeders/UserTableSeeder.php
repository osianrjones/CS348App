<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Two admins
        $user = new User;
        $user->name = "linus";
        $user->password = bcrypt("windows");
        $user->email = "linus.torvalds@linux.com";
        $user->email_verified_at = Carbon::now();
        $user->remember_token = Str::random(10);
        $user->isAdmin = true;
        $user->save();

        $user = new User;
        $user->name = "Gosling";
        $user->password = bcrypt("java");
        $user->email = "james.gosling@oracle.com";
        $user->email_verified_at = Carbon::now();
        $user->remember_token = Str::random(10);
        $user->isAdmin = true;
        $user->save();

        //Factory create non-admins
        User::factory()->count(50)->create();
    }
}
