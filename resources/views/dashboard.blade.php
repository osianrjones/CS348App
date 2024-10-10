<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Welcome! Here you can see all your posts:") }}
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($posts as $post)
                    <div class="p-4 border border-gray-200 rounded text-white flex flex-col justify-between h-full">
                        <img src="{{ $post->image_path }}" alt="Post Image" class="mb-4 max-w-full h-full object-cover">
                        <div class="mt-auto">
                            <p><strong>Post ID:</strong> {{ $post->id }}</p>
                            <p><strong>Created At:</strong> {{ $post->created_at }}</p>
                            <div class="flex items-center space-x-4">
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 px-10 hover:font-bold">Delete</button>
                                </form>
                                <a href="youtube.com" class="text-blue-600 hover:text-blue-800 px-20 mr-4">View Comments</a>
                            </div>
                        </div>
                    </form>
                    </div>
                @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
