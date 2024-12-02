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
                    {{ __("Welcome home :name! Here you can see all your profile posts:", ['name' => Auth::user()->name]) }}
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($posts as $post)
                    <div class="p-4 border border-gray-200 rounded text-white flex flex-col justify-between h-full">
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4 text-gray-900 dark:text-gray-100 flex justify-center items-center">
                        <form id="uploadPost" action="{{ route('posts.createPost') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <div class="flex justify-center mt-6 mb-6">
                            
                            <!-- Hidden File Input Triggered by Custom Button -->
                            <input type="file" name="image" id="image" class="hidden" required>
                            <button id="selectPost" style="background-color: #3b82f6;" type="button" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 font-bold" onclick="document.getElementById('image').click();">
                                New Post
                            </button>

                        </div>             
                        <div class="mt-6 mb-6 flex justify-center">
                        <input
                                name="location"
                                type="text"
                                class="border border-grey-300 rounded text-black"
                                placeholder="Enter post location..."
                            />
                        </div>      
                            <div id="previewContainer" class="flex justify-center" style = "display: none;">
                                <p class="font-bold">Image Preview:</p>
                                <img id="previewImage" src="#"  style="width: 420px; height: 420px; object-fit: cover;" class=" border-gray-300 rounded">
                            </div>
                            <div class="flex justify-center mt-6 mb-6">
                             <button id="uploadButton" style="display: none; background-color: #22c55e;" type="submit" class="dark:bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">
                               Upload
                            </button>
                             </div>
                        </form>    
                    </div>
        </div>
    </div>
    <script>
        document.getElementById('image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            console.log(file);
            const previewContainer = document.getElementById('previewContainer');
            const previewImage = document.getElementById('previewImage');
            const uploadButton = document.getElementById('uploadButton');
            const selectPostButton = document.getElementById('selectPost');

            if (file && file.type.startsWith('image/')) {
                previewContainer.style.display = 'block';
                previewImage.src = URL.createObjectURL(file);
                uploadButton.style.display = 'block';
                selectPostButton.innerHTML = "Change Post";
            } else {
                alert("Unable to upload this file.");
            }
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
