<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;

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
     * View lessons.
     *
     * @return view
     */
    public function index()
    {
        $lessons = Lesson::all();

        return view('admin.lessons')->with(['lessons' => $lessons]);
    }

    /**
     * Create lesson
     *
     * @return redirect
     */
    public function store()
    {
        if ($this->request->has('title')) {
            Lesson::create(['title' => $this->request->get('title')]);
        }

        return redirect()->to('admin');
    }
}
