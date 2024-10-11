<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Feed') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Your feed for today :name! Don't forget to leave a comment on a post you like:", ['name' => Auth::user()->name]) }}
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($posts as $post)
                    <div class="p-4 border border-gray-200 rounded text-white flex flex-col justify-between h-full">
                        <p><strong>User's Post:</strong> {{ $post->user->name }}</p><br>
                        <p><strong>Posted At:</strong> {{ $post->created_at }}</p><br>
                        <img src="{{ $post->image_path }}" alt="Post Image" class="mb-4 max-w-full h-full object-cover">
                        <div class="mt-auto">
                        @if ($post->comments->isNotEmpty())
                        <p><u><strong>Comments</strong></u></p>
                            <div>
                                <ul id="comments-{{$post->id}}">
                                    @foreach ($post->comments as $comment)
                                    <div class="flex justify-between items-center mb-2">
                                        <li>{{ $comment->comment }} <span>- {{ $comment->user->name }}</span><span class="text-gray-400 italic"> ({{$comment->created_at}})</span></li>
                                        
                                    </div> 
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                            <form id="form-{{$post->id}}" class="relative text-black" comment-route="{{ route('comments.create', $post->id) }}">
                                   @csrf
                                   <div class="flex items-start">
                                        <textarea id="content-{{$post->id}}" style="resize: none; width:100%" class="max-w-full h-11" name="content" placeholder="Leave a comment..." required></textarea>
                                        <button type="submit" class="hover:text-green-400">
                                            <i class="fa-solid fa-comment text-gray-400 px-4 py-6 text-2xl"></i>
                                        </button>
                                    </div>
                            </form>
                        </div>
                    </form>
                    </div>
                @endforeach
                </div>
                <div class="mt-6">
                    {{$posts -> links()}}
                </div>
                <div id="error" class="text-red-500" style="display:none;"></div>    
            </div>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('reloaded')
            //Loop over every comment box
            document.querySelectorAll('[id^="form-"]').forEach(function (formElement) {

                //Get the post ID
                let postId = formElement.id.split('-')[1];

                //Get the route from the form
                let route = formElement.getAttribute('comment-route');

                formElement.addEventListener('submit', function(event) {
                event.preventDefault();
                
                //Set AJAX varaiables
                let form = this;
                let content = document.getElementById('content-' + postId).value;

                //Remove any previous errors
                document.getElementById('error').style.display = 'none';

                //AJAX request
                fetch(route, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    },
                    body: JSON.stringify({
                        comment: content
                    })
                })
                .then(response => response.json()) //First convert response back to JSON
                .then(data => { //Extract JSON data
                    //Return code from PHP
                    if (data.success) {
                        //Create new comment from AJAX data.
                        let newComment = `<li>${data.comment.comment} <span>- ${data.user.name}</span><span class="text-gray-400 italic"> (${data.created_at})</span></li>`

                        //Append comment to the comments section
                        document.getElementById(`comments-${data.comment.post_id}`).innerHTML += newComment;

                        //Clear comment textbox
                        document.getElementById(`content-${data.comment.post_id}`).value = '';
                        
                    } else {
                        document.getElementById('error').textContent = data.error;
                        document.getElementById('error').style.display = 'block';

                        //Clear comment textbox
                        document.getElementById(`content-${data.comment.post_id}`).value = '';
                    }
                });
            });
         });
        });
    </script>   
</x-app-layout>
