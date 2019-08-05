<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->name = 'Admin';
        $user->username = 'admin';
        $user->password = Hash::make('pass1234');
        $user->role = 'ADMIN';
        $user->save();
    }
}
