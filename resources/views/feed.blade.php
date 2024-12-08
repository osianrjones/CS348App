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
                        <p><strong>User's Post:</strong><a href="{{ route('users.getUser', $post->user->name) }}"> {{ $post->user->name }}</a></p><br>
                        <p><strong>Posted At:</strong> {{ $post->created_at }}</p><br>
                        <div class="flex items-center space-x-2 mb-2 cursor-pointer" onclick="togglePopup('{{$post->location}}')">
                                <!-- Element to hover over -->                             
                                <i class="fas fa-map-marked-alt"></i><p>:</p><p id="location">{{$post->location}}</p>
                            

                           <!-- Iframe container (hidden by default) -->
                            <div id="popup" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                <div class="bg-white p-6 rounded-lg shadow-lg w-[600px] relative">
                                    <div class="flex justify-between items-center mb-2">
                                        <button onclick="togglePopup()" class="text-gray-500">&times;</button>
                                    </div>
                                    <iframe
                                        id="iframe" 
                                        width="100%" 
                                        height="200" 
                                        src="" 
                                        frameborder="0" 
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            </div> 
                        </div>
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
                                <ul id="comments-{{$post->id}}">
                                    @foreach ($post->comments as $comment)
                                    <div class="flex justify-between items-center mb-2">
                                        <li>{{ $comment->comment }} <span>-<a href="{{ route('users.getUser', $comment->user->name) }}"> {{ $comment->user->name }}</a></span><span class="text-gray-400 italic"> ({{$comment->created_at}})</span></li>
                                        @if ($comment->user->name == Auth::user()->name)
                                        <form action="{{ route('comments.deleteComment', $comment->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                            @csrf
                                            @method('DELETE')
                                            <!-- Delete Icon (using Font Awesome) -->
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        @endif
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
            console.log('reloaded');
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
                        let newComment = `<div class="flex justify-between items-center mb-2"> 
                                            <li>${data.comment.comment} <span>- ${data.user.name}</span><span class="text-gray-400 italic"> (${data.created_at})</span></li>`

                        let newDelete = `<form action="/comments/${data.comment.id}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                            @csrf
                                            @method('DELETE')
                                            <!-- Delete Icon (using Font Awesome) -->
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        </div>`

                        let newBlock = newComment.concat(newDelete);

                        //Append comment to the comments section
                        document.getElementById(`comments-${data.comment.post_id}`).innerHTML += newBlock;

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

        function togglePopup(location) {
            const popup = document.getElementById('popup');
            const iframe = document.getElementById('iframe');

            console.log(location);

            if (popup.classList.contains('hidden')) {
                iframe.src = 'https://www.google.com/maps/embed/v1/place?key=AIzaSyC4dDgA7NzpVG-0MCMnNku1g5S5mdJQbHo&q=' + location                                                                                                                                         ;
            } else {
                iframe.src = ''
            }

            popup.classList.toggle('hidden');
        }
    </script>   
</x-app-layout>
