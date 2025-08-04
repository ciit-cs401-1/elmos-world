<div class="bg-green-700 px-15 py-10 rounded-t-2xl text-white">
    <div class="flex flex-col md:flex-row justify-between gap-10 mb-25 flex-wrap">
    <!-- Links -->
    <div class="space-y-2">
        <div class="text-xl font-semibold">Links</div>
        <div><a href="{{route('landing')}}" class="hover:underline">Home</a></div>
        <div><a href="{{route('posts.index')}}" class="hover:underline">Blogs</a></div>
        <div><a href="#" class="hover:underline">About Us</a></div>
        <div><a href="#" class="hover:underline">Contact Us</a></div>
        <div><a href="#" class="hover:underline">Feedback</a></div>
    </div>

    <!-- Categories -->
    <div class="space-y-2">
        <div class="text-xl font-semibold ">Categories</div>
        <a class="block cursor-pointer" href="{{ route('posts.index', ['category' => "budgeting-savings"]) }}">Budgeting & Savings</a>
        <a class="block cursor-pointer" href="{{ route('posts.index', ['category' => "investing"]) }}">Investing</a>
        <a class="block cursor-pointer" href="{{ route('posts.index', ['category' => "debt-credit"]) }}">Debt & Credit</a>
        <a class="block cursor-pointer" href="{{ route('posts.index', ['category' => "financial-planning"]) }}">Financial Planning</a>
        <a class="block cursor-pointer" href="{{ route('posts.index', ['category' => "career-income"]) }}">Career & Income</a>
    </div>

    <!-- Socials -->
    <div class="space-y-2">
        <div class="text-xl font-semibold">Socials</div>
        <div><a href="#" class="hover:underline" href="www.facebook.com">Facebook</a></div>
        <div><a href="#" class="hover:underline" href="www.instagram.com">Instagram</a></div>
        <div><a href="#" class="hover:underline" href="www.x.com">X</a></div>
        <div><a href="#" class="hover:underline" href="www.tiktok.com">TikTok</a></div>
        <div><a href="#" class="hover:underline" href="www.linkedin.com">LinkedIn</a></div>
    </div>
</div>
           
    <div class="grid grid-cols-2">
        <div class="font-semibold text-2xl">Think Finance!</div>
        <div class="text-right">&copy; {{ date('Y') }} Think Finance! Ltd. All Rights Reserved.</div>
    </div>
</div>
