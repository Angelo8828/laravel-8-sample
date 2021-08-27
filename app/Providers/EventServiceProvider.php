<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Events\LessonWatched;
use App\Events\CommentWritten;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;

use App\Listeners\LessonWatchedL;
use App\Listeners\CommentWrittenL;
use App\Listeners\AchievementUnlockedL;
use App\Listeners\BadgeUnlockedL;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        CommentWritten::class => [
            CommentWrittenL::class
        ],
        LessonWatched::class => [
            LessonWatchedL::class
        ],
        AchievementUnlocked::class => [
            AchievementUnlockedL::class
        ],
        BadgeUnlocked::class => [
            BadgeUnlockedL::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
