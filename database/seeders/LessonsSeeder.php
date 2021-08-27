<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LessonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('lessons')->insert([
            'title' => 'Sample Lesson'
        ]);

        \DB::table('lessons')->insert([
            'title' => 'Lesson #1'
        ]);

        \DB::table('lessons')->insert([
            'title' => 'Lesson #2'
        ]);
    }
}
