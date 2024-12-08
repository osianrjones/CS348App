<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __(":name's Posts", ['name' => $tag->name]) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("All posts associated with tag :tag", ['tag' => $tag->name]) }}
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($posts as $post)
                    <div class="p-4 border border-gray-200 rounded text-white flex flex-col justify-between h-full">
                        <p><strong>Posted At:</strong> {{ $post->created_at }}</p><br>
                        <img src="{{ $post->image_path }}" alt="Post Image" class="mb-4 max-w-full h-full object-cover">
                        <div class="flex justify-stretch gap-2">
                            @foreach ($post->tags as $tag)
                                <a href="{{ route('posts.getByTag', $tag->name) }}"><span><i class="fa-solid fa-tag"></i>{{ $tag->name }}</span></a>
                            @endforeach
                        </div>    
                        <div class="mt-auto">
                        @if ($post->comments->isNotEmpty())
                        <p><u><strong>Comments</strong></u></p>
                            <div>
                                <ul>
                                    @foreach ($post->comments as $comment)
                                    <div class="flex justify-between items-center mb-2">
                                        <li>{{ $comment->comment }} <span>- {{ $comment->user->name }}</span><span class="text-gray-400 italic"> ({{$comment->created_at}})</span></li>
                                    </div> 
                                    @endforeach
                                </ul>
                            </div>

                        @endif
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
