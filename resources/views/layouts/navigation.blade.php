<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>
                <!-- Navigation Links -->              
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('feed')" :active="request()->routeIs('feed')">
                        {{ __('Feed') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Profile') }}
                    </x-nav-link>
                </div>
            </div>
            <div class="hidden sm:flex sm:items-center sm:ms-6">
            @if (Auth::user()->unreadNotifications->count() > 0)
                <x-dropdown align="right" width="48" class="relative">
                    <x-slot name="trigger">
                        <a id="navbarDropdown" class="inline-flex items-center px-3 py-2 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <i class="fa fa-bell"></i>
                                <span class="badge badge-heavy text-2xl bg-red-400 bg-success">  {{Auth::user()->unreadNotifications()->count()}}</span>
                        </a>
                    </x-slot>

                    <x-slot name="content">
                        <ul class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 font-bold pl-2">
                            <a href="{{route('markAsRead')}}" class="btn btn-success btn-sm">Mark All Read</a>
                        </ul>
                        @foreach (auth()->user()->unreadNotifications as $notification)
                            <a href="{{ route('users.getUser', $notification->data['user']) }}" class="text-success text-white"><li class="p-1 text-success">{{$notification->data['data']}}</li></a>
                        @endforeach
                    </x-slot>
                </x-dropdown>
            @else
                <div class="inline-flex items-center px-3 py-2 font-medium rounded-md text-gray-500 cursor-default">
                    <i class="fa fa-bell"></i>
                    <span class="badge badge-light">0</span>
                </div>
            @endif
            </div>
        @if (session('message'))
            <div
                id="sessionNotificationDropdown"
                class="absolute top-10 right-0 w-64 p-4 bg-gray-800 text-green-500 rounded shadow-lg opacity-100 transition-opacity duration-500 ease-in-out"
                style="display: none;"
            >
                <div class="flex justify-between items-center text-green-500">
                    <span>{{ session('message') }}</span>
                    <button id="sessionCloseNotification" class="text-green-500 hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif
        @if ($errors->any())
            <div
                id="errorNotificationDropdown"
                class="absolute top-10 right-0 w-64 p-4 bg-gray-800 text-red-500 rounded shadow-lg opacity-100 transition-opacity duration-500 ease-in-out"
                style="display: none;"
            >
                <ul>
                    @foreach ($errors->all() as $error)
                    <div class="flex justify-between items-center">
                        <span>{{ $error }}</span>
                        <button id="errorCloseNotification" class="text-gray-400 hover:text-gray-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    @endforeach
                </ul>
            </div>
        @endif     
            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Settings') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('sessionNotificationDropdown')) {
            const notificationDropdown = document.getElementById('sessionNotificationDropdown');
            notificationDropdown.style.display = 'block';

            // Automatically fade out the notification after 5 seconds
            setTimeout(function () {
                notificationDropdown.classList.add('opacity-0');
                setTimeout(function () {
                    notificationDropdown.style.display = 'none';
                }, 500); // Wait for the fade-out animation to finish
            }, 5000); // Wait for 5 seconds before fading out

            // Close the notification manually when the close button is clicked
            document.getElementById('sessionCloseNotification').addEventListener('click', function () {
                notificationDropdown.classList.add('opacity-0');
                setTimeout(function () {
                    notificationDropdown.style.display = 'none';
                }, 500);
            });
        }
        if (document.getElementById('errorNotificationDropdown')) {
            const notificationDropdown = document.getElementById('errorNotificationDropdown');
            notificationDropdown.style.display = 'block';

            // Automatically fade out the notification after 5 seconds
            setTimeout(function () {
                notificationDropdown.classList.add('opacity-0');
                setTimeout(function () {
                    notificationDropdown.style.display = 'none';
                }, 500); // Wait for the fade-out animation to finish
            }, 5000); // Wait for 5 seconds before fading out

            // Close the notification manually when the close button is clicked
            document.getElementById('errorCloseNotification').addEventListener('click', function () {
                notificationDropdown.classList.add('opacity-0');
                setTimeout(function () {
                    notificationDropdown.style.display = 'none';
                }, 500);
            });
        }
    });
    </script>
</nav>
