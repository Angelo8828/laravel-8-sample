<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\BadgeUnlocked;

use App\Models\UserAchievement;

class BadgeUnlockedL
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
    public function handle(BadgeUnlocked $event)
    {
        UserAchievement::create([
            'user_id'        => $event->user->id,
            'achievement_id' => $event->achievement->id,
        ]);
    }
}
