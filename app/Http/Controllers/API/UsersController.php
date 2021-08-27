<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;

class UsersController extends Controller
{
    /**
     * Show achievement stats of user
     *
     * @param  int $userId
     *
     * @return json
     */
    public function showAchievements($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'Invalid user ID'])
                ->setStatusCode(404);
        }

        $achievementTypes = Achievement::select('type')
            ->groupBy('type')
            ->get();

        $achievements = Achievement::orderBy('type')
            ->orderBy('requirement', 'DESC')
            ->get();

        $unlockedAchievements = [];
        $currentBadge = ['title' => 'Beginner'];
        foreach ($user->achievements as $userAchievement) {
            if ($userAchievement->type == 'badges') {
                $currentBadge = [
                    'title' => $userAchievement->title
                ];

                continue;
            }

            $unlockedAchievements[] = [
                'type'  => $userAchievement->type,
                'title' => $userAchievement->title,
            ];
        }

        $nextAvailableAchievements = [];
        $nextBadge = [];
        $nextBadgeRequirement = 0;

        foreach ($achievementTypes as $achievementType) {
            $achievementType = $achievementType->type;

            $unlockedTypeAchievements = [];
            $nextAvailableAchievement = [];

            foreach ($unlockedAchievements as $unlockedAchievement) {
                if ($unlockedAchievement['type'] == $achievementType) {
                    $unlockedTypeAchievements[] = $unlockedAchievement['title'];
                }
            }

            foreach ($achievements as $achievement) {
                if (!in_array($achievement->title, $unlockedTypeAchievements) && $achievement->type == $achievementType && $achievement->type != 'badges') {
                    $nextAvailableAchievement = [
                        'type'  => $achievement->type,
                        'title' => $achievement->title,
                    ];
                }
            }

            foreach ($achievements as $achievement) {
                if ($achievement->type == 'badges' && $achievement->title != $currentBadge['title']) {
                    $nextBadge = [
                        'title' => $achievement->title
                    ];

                    $nextBadgeRequirement = $achievement->requirement;
                } else {
                    break;
                }
            }

            if ($nextAvailableAchievement) {
                $nextAvailableAchievements[] = $nextAvailableAchievement;
            }
        }

        $remainingToUnlockNextBadge = $nextBadgeRequirement - $user->achievements
            ->where('type', '!=', 'badges')
            ->count();

        return response()->json([
            'unlocked_achievements' => $unlockedAchievements,
            'next_available_achievements' => $nextAvailableAchievements,
            'current_badge' => $currentBadge,
            'next_badge' => $nextBadge,
            'remaining_to_unlock_next_badge' => $remainingToUnlockNextBadge
        ]);
    }
}
