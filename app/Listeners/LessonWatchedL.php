<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\LessonWatched;
use App\Events\AchievementUnlocked;

use App\Models\Achievement;
use App\Models\LessonUser;
use App\Models\User;

class LessonWatchedL
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(LessonWatched $event)
    {
        LessonUser::create([
            'lesson_id' => $event->lesson->id,
            'user_id'   => $event->user->id,
            'watched'   => 1
        ]);

        $userViewCount = LessonUser::where('user_id', $event->user->id)
            ->where('watched', 1)
            ->count();

        $lessonsWatchedAchievements = Achievement::where('type', 'lessons_watched')
            ->orderBy('requirement')
            ->get();

        $userAchievements = $event->user->achievements()->allRelatedIds();
        $userAchievementsArray = [];
        foreach ($userAchievements as $userAchievementId) {
            $userAchievementsArray[] = $userAchievementId;
        }

        foreach ($lessonsWatchedAchievements as $achievement) {
            if (
                $userViewCount >= $achievement->requirement &&
                !in_array($achievement->id, $userAchievementsArray)
            ) {
                event(new AchievementUnlocked($event->user, $achievement));

                break;
            }
        }
    }
}
