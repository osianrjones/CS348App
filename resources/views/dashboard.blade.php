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
                    {{ __("Welcome home, :name! Here you can see all your profile posts:", ['name' => Auth::user()->name]) }}
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($posts as $post)
                    <div class="p-4 border border-gray-200 rounded text-white flex flex-col justify-between h-full">
                        <p><strong>Posted At:</strong> {{ $post->created_at }}</p><br>
                        <img src="{{ $post->image_path }}" alt="Post Image" class="mb-4 max-w-full h-full object-cover">
                        <div class="mt-auto">
                        @if ($post->comments->isNotEmpty())
                        <p><u><strong>Comments</strong></u></p>
                            <div>
                                <ul>
                                    @foreach ($post->comments as $comment)
                                    <div class="flex justify-between items-center mb-2">
                                        <li>{{ $comment->comment }} <span>- {{ $comment->user->name }}</span><span class="text-gray-400 italic"> ({{$comment->created_at}})</span></li>
                                        <br>
                                        <form action="{{ route('comments.deleteComment', $comment->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                            @csrf
                                            @method('DELETE')
                                            <!-- Delete Icon (using Font Awesome) -->
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div> 
                                    @endforeach
                                </ul>
                            </div>

                        @endif
                            <div class="flex items-center space-x-4 mb-0">
                                <form action="{{ route('posts.deletePost', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 px-10 hover:font-bold">Delete</button>
                                </form>
                            </div>
                        </div>
                    </form>
                    </div>
                @endforeach
                </div>
                <div class="mt-6">
                    {{$posts -> links()}}
                </div>    
            </div>

        </div>
    </div>
</x-app-layout>
