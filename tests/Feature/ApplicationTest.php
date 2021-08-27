<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Illuminate\Testing\Fluent\AssertableJson;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\CommentWritten;
use App\Events\LessonWatched;

use App\Listeners\CommentWrittenL;
use App\Listeners\LessonWatchedL;

use App\Models\Comment;
use App\Models\Lesson;
use App\Models\LessonUser;
use App\Models\User;

use Database\Seeders\AchievementsSeeder;

class ApplicationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_can_visit_user_achievement_stats()
    {
        $user = User::factory()->create();

        $response = $this->json("GET", "api/users/{$user->id}/achievements");

        $response->assertStatus(200);

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('unlocked_achievements')
                    ->has('next_available_achievements')
                    ->has('current_badge')
                    ->has('next_badge')
                    ->has('remaining_to_unlock_next_badge')
            );
    }

    public function test_can_visit_lessons()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('user::view-lessons'));

        $response->assertStatus(200);
    }

    public function test_can_view_individual_lesson_and_an_event_triggers()
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        Event::fake();

        $response = $this->actingAs($user)->get(route('user::view-lesson', ['lessonId' => $lesson->id]));

        $response->assertStatus(200);

        Event::assertDispatched(LessonWatched::class);
    }

    public function test_can_view_individual_lesson_and_the_correct_event_triggers()
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        Event::fake();

        $response = $this->actingAs($user)->get(route('user::view-lesson', ['lessonId' => $lesson->id]));

        $response->assertStatus(200);

        Event::assertNotDispatched(CommentWritten::class);
    }

    public function test_cannot_view_individual_lesson_if_unathenticated()
    {
        $lesson = Lesson::factory()->create();

        $response = $this->get(route('user::view-lesson', ['lessonId' => $lesson->id]));

        $response->assertStatus(302);
    }

    public function test_can_view_lesson_and_an_achievement_is_unlocked()
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        $this->seed(AchievementsSeeder::class);

        Event::fake();

        $listener = new LessonWatchedL;
        $listener->handle(new LessonWatched($lesson, $user));

        Event::assertDispatched(AchievementUnlocked::class);
    }

    public function test_can_post_comment_and_an_event_triggers()
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        Event::fake();

        $response = $this->actingAs($user)->post(route('user::post-comment', ['lessonId' => $lesson->id]), [
            'comment' => 'Lorem ipsum dolor...',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('user::view-lesson', ['lessonId' => $lesson->id]));

        $this->assertDatabaseHas('comments', ['body'=>'Lorem ipsum dolor...']);

        Event::assertDispatched(CommentWritten::class);
    }

    public function test_can_post_comment_and_an_achievement_is_unlocked()
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        $this->seed(AchievementsSeeder::class);

        $comment =  Comment::factory()->create([
            'user_id'   => $user->id,
            'lesson_id' => $lesson->id,
            'body'      => 'Lorem ipsum dolor...'
        ]);

        Event::fake();

        $listener = new CommentWrittenL;
        $listener->handle(new CommentWritten($comment));

        Event::assertDispatched(AchievementUnlocked::class);
    }

    public function test_are_achievement_stats_accurate()
    {
        $this->seed(AchievementsSeeder::class);

        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        for ($i=0; $i < 10; $i++) {
            $listener = new LessonWatchedL;
            $listener->handle(new LessonWatched($lesson, $user));
        }

        $comments =  Comment::factory()->count(10)->create([
            'user_id'   => $user->id,
            'lesson_id' => $lesson->id,
            'body'      => 'Lorem ipsum dolor...'
        ]);

        $listenerII = new CommentWrittenL;

        foreach ($comments as $comment) {
            $listenerII->handle(new CommentWritten($comment));
        }

        $response = $this->json("GET", "api/users/{$user->id}/achievements");

        $response->assertStatus(200);

        // mock response code
        /*
        array:5 [
            "unlocked_achievements" => array:7 [
                0 => array:2 [
                    "type" => "lessons_watched"
                    "title" => "First Lesson Watched"
                ]
                1 => array:2 [
                    "type" => "lessons_watched"
                    "title" => "5 Lessons Watched"
                ]
                2 => array:2 [
                    "type" => "lessons_watched"
                    "title" => "10 Lessons Watched"
                ]
                3 => array:2 [
                    "type" => "comments_written"
                    "title" => "First Comment Written"
                ]
                4 => array:2 [
                    "type" => "comments_written"
                    "title" => "3 Comments Written"
                ]
                5 => array:2 [
                    "type" => "comments_written"
                    "title" => "5 Comments Written"
                ]
                6 => array:2 [
                    "type" => "comments_written"
                    "title" => "10 Comments Written"
                ]
            ]
            "next_available_achievements" => array:2 [
                0 => array:2 [
                    "type" => "comments_written"
                    "title" => "20 Comments Written"
                ]
                1 => array:2 [
                    "type" => "lessons_watched"
                    "title" => "25 Lessons Watched"
                ]
            ]
            "current_badge" => array:1 [
                "title" => "Intermediate"
            ]
            "next_badge" => array:1 [
                "title" => "Advanced"
            ]
            "remaining_to_unlock_next_badge" => 1
            ]
        */

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('unlocked_achievements', 7)
                    ->has('next_available_achievements', 2)
                    ->has('current_badge')
                    ->where('current_badge.title', 'Intermediate')
                    ->has('next_badge')
                    ->where('next_badge.title', 'Advanced')
                    ->has('remaining_to_unlock_next_badge')
                    ->where('remaining_to_unlock_next_badge', 1)
            );
    }
}
