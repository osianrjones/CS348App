<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 relative">
        <div class="bg-white dark:bg-gray-800 text-white overflow-hidden shadow-sm sm:rounded-lg">
            <h2 class="mt-4 px-4 text-xl">
                <u>Filter</u>
            </h2>
            <form method="GET" action="{{ route('admin') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 px-4 text-lg">
                 <div>
                  <p>User:</p>
                  <select id="user-select" name="user" class="select2 mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                      <option value="">Select a user...</option>
                     <!-- Add options dynamically in Laravel -->
                     @foreach ($users as $user)
                       <option value="{{$user->id}}">{{ $user->name }}</option> 
                     @endforeach
                 </select>
                </div>
                <div>
                    <p>Post Date and Time:</p>
                    <select id="post-select" name="post" class="select2 mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                      <option value="">Select a post date and time...</option>
                     <!-- Add options dynamically in Laravel -->
                     @foreach ($postDatesTimes as $postDateTime)
                        <option value="{{$postDateTime}}">{{ $postDateTime }}</option>
                     @endforeach
                 </select>
                </div>
                <div>
                    <p>Location:</p>
                    <select id="location-select" name="location" class="select2 mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                      <option value="">Select a post location...</option>
                     <!-- Add options dynamically in Laravel -->
                     @foreach ($locations as $location)
                        <option value="{{$location}}">{{ $location }}</option>
                     @endforeach
                 </select>
                </div>
            </div>
                <div class="px-4 py-4 flex justify-end items-end">
                    <button type="submit" class="border rounded-md border-green-500 px-2 py-2" style="background-color: #669900; margin-right: 0.5rem; border-color: rgb(34 197 94 / var(--tw-border-opacity));">
                        Search
                    </button>
            </form>
           
            </div>
        </div>
    </div>
        

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            @if (count($posts) != 0)
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Here is your filtered feed :name!", ['name' => Auth::user()->name]) }}
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($posts as $post)
                        <div class="p-4 border border-gray-200 rounded text-white flex flex-col justify-between h-full relative">
                            <div class="flex justify-between">
                                <p><strong>User's Post:</strong>
                                <a href="{{ route('users.getUser', $post->user->name) }}">
                                    {{ $post->user->name }}
                                    @if ($post->user->name == Auth::user()->name)
                                    <i class="fas fa-crown dark:text-yellow-400 ml-2"></i>
                                    @endif
                                    </a>
                                </p>                              
                                <form method="POST" action="{{ route('posts.deletePost', $post->id) }}">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="ml-6 border rounded-md border-red-600 text-red-600 px-2 py-2 cursor-pointer" style="border-color: rgb(220 38 38 / var(--tw-border-opacity));">
                                            Delete
                                        </button>
                                </form>
                            </div>
                            <br> 
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
                            @endif
                                <div>
                                    <ul id="comments-{{$post->id}}">
                                        <div class="hidden">
                                            <p>Test</p>
                                        </div>
                                        @foreach ($post->comments as $comment)
                                        <div class="flex justify-between items-center mb-2">
                                            <li>{{ $comment->comment }} <span>-<a href="{{ route('users.getUser', $comment->user->name) }}"> {{ $comment->user->name }}</a></span><span class="text-gray-400 italic"> ({{$comment->created_at}})</span></li>
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
                        </div>
                @endforeach
                @else
                <div class="text-white flex justify-center px-10 py-10 ">
                    <p>No posts found. Search using the filter to start seeing posts!</p>
                </div>
                @endif
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
                iframe.src = 'https://www.google.com/maps/embed/v1/place?key=' + location                                                                                                                                         ;
            } else {
                iframe.src = ''
            }

            popup.classList.toggle('hidden');
        }

        $(document).ready(function () {
            $('location-select').select2();
        });

        $(document).ready(function () {
            $('user-select').select2();
        });

        $(document).ready(function () {
            $('post-select').select2();
        });
    </script>   
</x-app-layout>
