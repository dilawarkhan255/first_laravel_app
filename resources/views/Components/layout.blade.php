<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Jobs Application</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="h-full bg-gray-100">
        <div class="min-h-full">
            <nav class="bg-gray-800">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <img class="h-8 w-8" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company">
                            </div>
                            <div class="hidden md:block">
                                <div class="ml-10 flex items-baseline space-x-4">
                                    {{-- <a class="nav-link {{ $current == url('/') ? 'active' : ''  }} {{ $current != url('/') ? 'header_class' : '' }}"
                                    href="/" style="color: #2f6293;">Home</a> --}}
                                    {{-- <a href="/" class="rounded-md {{ Request::is('/') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" aria-current="page" style="text-decoration:none;">Home</a> --}}
                                    @auth
                                        <a href="/jobs/home" class="rounded-md {{ Request::is('jobs/home') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" aria-current="page" style="text-decoration:none;">Dashboard</a>
                                        <a href="/jobs" class="rounded-md {{ Request::is('jobs') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" style="text-decoration:none;">Jobs</a>
                                        <a href="/designations" class="rounded-md {{ Request::is('designations') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" style="text-decoration:none;">Job Designations</a>
                                        <a href="/students" class="rounded-md {{ Request::is('students') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" style="text-decoration:none;">Students</a>
                                        <a href="/subjects" class="rounded-md {{ Request::is('subjects') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" style="text-decoration:none;">Subjects</a>
                                        <a href="/roles" class="rounded-md {{ Request::is('roles') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" aria-current="page" style="text-decoration:none;">Roles</a>
                                        <a href="/users" class="rounded-md {{ Request::is('users') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" aria-current="page" style="text-decoration:none;">Users</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-4 flex items-center md:ml-6">
                                @guest
                                    <a href="/login" class="rounded-md {{ Request::is('login') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" style="text-decoration:none;">Log In</a>
                                    <a href="/register" class="rounded-md {{ Request::is('register') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" style="text-decoration:none;">Register</a>
                                @endguest

                                @auth
                                <div class="flex items-center space-x-4">
                                    <div class="relative">
                                        <img class="h-8 w-8 rounded-full cursor-pointer" src="{{ asset('images/' . (Auth::user()->profile_image ?? 'default-image.png')) }}"alt="{{ Auth::user()->name }}"
                                            id="dropdownMenuButton"
                                            onclick="toggleDropdown()">
                                        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-lg shadow-lg z-10">
                                            <div class="p-4 text-center">
                                                <form action="{{ route('user.upload_image') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="image">Upload Image</label>
                                                        <input type="file" class="form-control" id="image" name="image">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-white text-sm font-medium">{{ Auth::user()->name }}</span>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                                @endauth
                            </div>
                        </div>
                        <div class="-mr-2 flex md:hidden">
                            <button type="button" class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" aria-controls="mobile-menu" aria-expanded="false">
                                <span class="absolute -inset-0.5"></span>
                                <span class="sr-only">Open main menu</span>
                                <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                                <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="md:hidden" id="mobile-menu">
                    <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
                        <a href="/" class="rounded-md {{ Request::is('/') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" aria-current="page" style="text-decoration:none;">Home</a>
                        <a href="/about" class="rounded-md {{ Request::is('about') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" style="text-decoration:none;">About</a>
                        <a href="/contact" class="rounded-md {{ Request::is('contact') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" style="text-decoration:none;">Contact</a>
                        @auth
                            <a href="/jobs/home" class="rounded-md {{ Request::is('jobs/home') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" aria-current="page" style="text-decoration:none;">Dashboard</a>
                            <a href="/jobs" class="rounded-md {{ Request::is('jobs') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" style="text-decoration:none;">Jobs</a>
                            <a href="/designations" class="rounded-md {{ Request::is('designations') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" style="text-decoration:none;">Job Designations</a>
                            <a href="/students" class="rounded-md {{ Request::is('students') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" style="text-decoration:none;">Students</a>
                            <a href="/subjects" class="rounded-md {{ Request::is('subjects') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" style="text-decoration:none;">Subjects</a>
                            <a href="roles" class="rounded-md {{ Request::is('roles') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" aria-current="page" style="text-decoration:none;">Roles</a>
                        @endauth
                    </div>
                    <div class="border-t border-gray-700 pb-3 pt-4">
                        <div class="space-y-1 px-2">
                            @guest
                                <a href="/login" class="rounded-md {{ Request::is('login') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" style="text-decoration:none;">Log In</a>
                                <a href="/register" class="rounded-md {{ Request::is('register') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} px-3 py-2 text-sm font-medium" style="text-decoration:none;">Register</a>
                            @endguest
                            @auth
                                <div class="flex items-center space-x-4">
                                    <img class="h-8 w-8 rounded-full" src="{{ Auth::user()->profile_image_url ?? '#' }}" alt="{{ Auth::user()->name }}">
                                    <span class="text-white text-sm font-medium">{{ Auth::user()->name }}</span>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>
            <header class="bg-white shadow">
                <div class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $heading }}</h1>
                </div>
            </header>
            <main>
                <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>
        </div>

        <script>
            function toggleDropdown() {
                var dropdownMenu = document.getElementById("dropdownMenu");
                dropdownMenu.classList.toggle("hidden");
            }
            </script>
    </body>
</html>
