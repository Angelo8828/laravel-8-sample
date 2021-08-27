<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Libraries\Achievements;

class AchievementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lessonsWatchedAchievements = Achievements::lessonsWatched();
        $commentsWrittenAchievements = Achievements::commentsWritten();
        $badgesAchievements = Achievements::badges();

        foreach ($lessonsWatchedAchievements as $key => $value) {
            \DB::table('achievements')->insert([
                'title'       => $value,
                'requirement' => $key,
                'type'        => 'lessons_watched',
            ]);
        }

        foreach ($commentsWrittenAchievements as $key => $value) {
            \DB::table('achievements')->insert([
                'title'       => $value,
                'requirement' => $key,
                'type'        => 'comments_written',
            ]);
        }

        foreach ($badgesAchievements as $key => $value) {
            \DB::table('achievements')->insert([
                'title'       => $value,
                'requirement' => $key,
                'type'        => 'badges',
            ]);
        }
    }
}
