<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('View Lesson: ' . $lesson->title) }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">

          <form action="{{ route('user::post-comment', $lesson->id) }}" method="POST">

            {{ csrf_field() }}

            <div class="form-group">
              <label for="content" class="control-label">Comment</label><br>
              <textarea name="comment" class="form-control" required></textarea>
            </div>

            <button type="submit" class="btn btn-default">Submit</button>
          </form>

          <p><br/></p>

          <h3>Comments</h3>
          @foreach ($lesson->comments as $comment)
            {{ $comment->body }} - {{ $comment->user->email }}
            <br>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
