<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert([
            'name'     => 'User 1',
            'email'    => 'user@example.com',
            'password' => \Hash::make('password'),
        ]);

        \DB::table('users')->insert([
            'name'     => 'Angelo',
            'email'    => 'angelo8828@gmail.com',
            'password' => \Hash::make('password'),
        ]);
    }
}
