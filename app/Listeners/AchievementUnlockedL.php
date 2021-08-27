<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;

use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;

class AchievementUnlockedL
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
    public function handle(AchievementUnlocked $event)
    {
        UserAchievement::create([
            'user_id'        => $event->user->id,
            'achievement_id' => $event->achievement->id,
        ]);

        $userAchievementCount = UserAchievement::where('user_id', $event->user->id)
            ->whereHas('achievement', function ($query) {
                $query = $query->where('achievements.type', '!=', 'badges');
            })
            ->count();

        $listOfAllBadges = Achievement::where('type', 'badges')
            ->orderBy('requirement')
            ->get();

        $currentBadges = UserAchievement::where('user_id', $event->user->id)
            ->whereHas('achievement', function ($query) {
                $query = $query->where('achievements.type', 'badges');
            })
            ->get();

        $currentUserBadgesIds = [];
        foreach ($currentBadges as $badge) {
            $currentUserBadgesIds[] = $badge->achievement_id;
        }

        foreach ($listOfAllBadges as $achievement) {
            if (
                $userAchievementCount >= $achievement->requirement &&
                !in_array($achievement->id, $currentUserBadgesIds)
            ) {
                event(new BadgeUnlocked($event->user, $achievement));

                break;
            }
        }
    }
}
