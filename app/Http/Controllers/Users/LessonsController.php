<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Events\LessonWatched;
use App\Events\CommentWritten;

use App\Models\Comment;
use App\Models\Lesson;

class LessonsController extends Controller
{
    /**
     * The property that will contain the Illuminate\Http\Request.
     *
     * @var object
     */
    protected $request;

    /**
     * Class constructor.
     *
     * @param object $request Instance of Illuminate\Http\Request
     *
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * List all lessons.
     *
     * @return view
     */
    public function index()
    {
        $lessons = Lesson::all();

        return view('users.lessons.index')->with(['lessons' => $lessons]);
    }

    /**
     * View lesson
     *
     * @return view
     */
    public function get($lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);

        if (!$this->request->session()->has('success')) {
            event(new LessonWatched($lesson, auth()->user()));
        }

        return view('users.lessons.get')->with(['lesson' => $lesson]);
    }

    /**
     * Post comment on lesson
     *
     * @return view
     */
    public function postComment($lessonId)
    {
        if ($this->request->has('comment')) {
            $comment = Comment::create([
                'user_id'   => auth()->user()->id,
                'lesson_id' => $lessonId,
                'body'      => $this->request->get('comment')
            ]);

            event(new CommentWritten($comment));
        }

        return redirect()->route('user::view-lesson', ['lessonId' => $lessonId])
            ->with('success', 'Comment posted');
    }
}
