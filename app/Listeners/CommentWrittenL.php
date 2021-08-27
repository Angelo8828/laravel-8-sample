<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\CommentWritten;
use App\Events\AchievementUnlocked;

use App\Models\Achievement;
use App\Models\Comment;
use App\Models\User;

class CommentWrittenL
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
    public function handle(CommentWritten $event)
    {
        $userCommentCount = Comment::where('user_id', $event->comment->user_id)
            ->count();

        $commentsWrittenAchievements = Achievement::where('type', 'comments_written')
            ->orderBy('requirement')
            ->get();

        $user = User::find($event->comment->user_id);

        $userAchievements = $user->achievements()->allRelatedIds();
        $userAchievementsArray = [];
        foreach ($userAchievements as $userAchievementId) {
            $userAchievementsArray[] = $userAchievementId;
        }

        foreach ($commentsWrittenAchievements as $achievement) {
            if (
                $userCommentCount >= $achievement->requirement &&
                !in_array($achievement->id, $userAchievementsArray)
            ) {
                event(new AchievementUnlocked($user, $achievement));

                break;
            }
        }
    }
}
