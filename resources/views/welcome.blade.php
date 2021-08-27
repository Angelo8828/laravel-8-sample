<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home page') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <a class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600" href="{{ url('admin') }}">Go to admin</a>

                    <p><br/></p>

                    <a class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600" href="{{ url('lessons') }}">View lessons</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
