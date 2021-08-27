<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Manage Lessons') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <table class="min-w-full table-auto mt-4">
            <thead>
              <tr>
                <th>Lesson</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($lessons as $lesson)
                <tr>
                  <td>{{ $lesson->title }}</td>
                  <td>
                    <a href="#">Edit</a> |
                    <a href="#">Delete</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>

          <p><br /></p>

          <form action="{{ url('admin') }}" method="POST">
            <div class="form-group">
              {{ csrf_field() }}
              <label for="title" class="control-label">Title</label>
              <input class="form-control" placeholder="Please enter title." name="title" type="text" id="title" required>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
