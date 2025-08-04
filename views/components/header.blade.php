<div class="px-15 py-7 grid grid-cols-4 items-center">
    <a class="col-span-1 font-semibold text-green-700 text-2xl cursor-pointer" href="{{route('landing')}}">Think Finance!</a>
    <div class="col-span-2 font-semibold text-lg flex gap-15 justify-center">
        <a class="cursor-pointer transition ease-in-out hover:text-green-700" href="{{route('landing')}}">Home</a>
        <a class="cursor-pointer transition ease-in-out hover:text-green-700" href="{{route('posts.index')}}">Blogs</a>

        @auth
            @if (!auth()->user()->hasRole('S'))
                <a class="cursor-pointer transition ease-in-out hover:text-green-700" href="{{route('posts.create')}}">Create</a>
            @endif
        @endauth

    </div>
    <div class="col-span-1 font-semibold text-lg flex gap-4 justify-end relative group items-center">

        @auth
            <div class="text-right flex-row gap-2">
                <span class="block text-sm text-gray-400">Welcome,</span>
                <span class="block text-lg">{{auth()->user()->name}}</span>
            </div>
        @endauth

        <button class="bg-green-700 rounded-full text-white p-2 cursor-pointer" >
            <x-heroicon-o-user-circle class="h-8"/>
        </a>

        <div class="absolute right-0 top-[10] w-50 z-100 bg-white rounded-lg shadow-xl border border-gray-200 invisible group-hover:opacity-100 group-hover:visible transition">
        
            @auth
            @role('A', 'C')
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                Dashboard
            </a> 
            @endrole

            @role('S')
            <a href="{{ route('profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                Profile
            </a> 
            @endrole

            <a href="{{ route('logout') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a> 
            <form method="POST" id="logout-form" action="{{route('logout')}}" class="hidden">
                @csrf
            </form>            
            @else
            <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                Login
            </a>  
            @endauth

        </div>
    </div>
</div>
