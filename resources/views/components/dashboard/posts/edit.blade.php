@extends("layouts.dashboard")

@section('content')
<div class="px-15 mb-10 mt-10 max-w-2xl mx-auto space-y-8">
    <a class="flex items-center gap-2 mb-10 mt-5 text-green-800 cursor-pointer" href="{{route('dashboard.posts.show', $post->id)}}"><x-ri-arrow-left-long-line class="h-5" />Back</a>
    <!-- 💬 Comment Form -->
    <div class="space-y-3">
        <h2 class="text-xl font-semibold">Update the blog</h2>
        <form action="{{ route('posts.update', $post->id) }}" method="POST">
            @csrf <!-- for security reasons -->
            @method('PUT')
            <input name="post_title" type="text" placeholder="Title" class="w-full h-10 p-4 mb-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('title', $post->title) }}"/>
            <textarea
                name="post_content"
                placeholder="Write your content here..."
                class="w-full h-50 p-4 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('content', $post->content) }}</textarea>
            <input name="post_image" type="image-link" placeholder="Image Link" class="w-full h-10 p-4 mb-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('featured_image_url', $post->featured_image_url) }}"/>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 my-2">
                    Select Categories:
                </label>
                <div class="grid mb-5">
                    @foreach ($categories as $category)
                    <label class="inline-flex items-center">
                        <input
                            type="checkbox"
                            name="post_categories[]"
                            value="{{ $category->id }}"
                            class="form-checkbox text-blue-600"
                            {{ in_array($category->id, old('post_category', $post->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700">{{ $category->category_name }}</span>
                    </label>
                    @endforeach
                </div>
                <!-- TAG INPUT -->
                <div id="tagBox" class="w-full mb-3">
                    <!-- existing tags get injected here -->
                    <div id="tagContainer" class="flex flex-wrap gap-2 mb-2"></div>

                    <!-- the input -->
                    <input id="tag_input" type="text" placeholder="Add a tag and press Enter"
                        class="w-full h-10 p-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />

                    <!-- Hidden field to store submitted tag IDs -->
                    <input type="text" name="post_tags[]" id="hidden_tag_input" readonly>
                </div>

                <script>
                    // Step 1: Prefill tagmap with tags
                    console.log("prefilling tag_name with tag_names");
                    const tag_names = [
                        @foreach($tags as $tag)
                            "{{ $tag->tag_name }}",
                        @endforeach
                    ];

                    // Step 2: Take note of what is already displayed on page.
                    const already_displayed_tag_names = [];
                    
                    const input = document.getElementById('tag_input');
                    const container = document.getElementById('tagContainer');
                    const hiddenInput = document.getElementById('hidden_tag_input');

                    function renderTags() {
                        console.log("tag-names:", tag_names);
                        console.log("rendered-tag-names:", already_displayed_tag_names);
                        tag_names.forEach( (tag_name, index) => {
                            console.log(tag_name, index);
                            if (already_displayed_tag_names.includes(tag_name) == false) {
                                console.log("tag doesn't exist in display. Render this tag");
                                const chip_element = document.createElement('span');
                                chip_element.innerHTML = '';
                                chip_element.className = 'chip-design'; // chip-design in app.css
                                chip_element.innerHTML = `${tag_name}<button type="button" data-index="${index}" class="text-blue-500 hover:text-blue-700">&times;</button>`;
                                container.appendChild(chip_element);
                                already_displayed_tag_names.push(tag_name);
                            }
                        });
                        hiddenInput.value = JSON.stringify(tag_names);
                    }

                    input.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' || e.key === ',') {
                            e.preventDefault();
                            if (input.value.trim()) {
                                const tag = input.value.trim();
                                var does_tag_exist_in_tag_name = tag_names.includes(tag);

                                if (does_tag_exist_in_tag_name == false) {
                                    tag_names.push(tag);
                                    renderTags();
                                };
                                input.value = '';
                            };
                        };
                    });
                    

                    container.addEventListener('click', (e) => {
                        if (e.target.tagName === 'BUTTON') {
                            const chip = e.target.parentElement;
                            const tagText = chip.textContent.replace('×', '').trim();

                            // Remove the tag from both arrays
                            const tagIndex = tag_names.indexOf(tagText);
                            if (tagIndex !== -1) {
                                tag_names.splice(tagIndex, 1);
                            }

                            const displayedIndex = already_displayed_tag_names.indexOf(tagText);
                            if (displayedIndex !== -1) {
                                already_displayed_tag_names.splice(displayedIndex, 1);
                            }

                            // Remove the chip element directly
                            container.removeChild(chip);

                            // Update hidden input
                            hiddenInput.value = JSON.stringify(tag_names);
                        }
                    });


                    // Initial render
                    renderTags();
                </script>

                {{-- SUBMIT BUTTON --}}
                <div class="flex justify-center">
                    <button class="px-10 py-3 mt-5 bg-green-800 text-white rounded-2xl transition cursor-pointer">
                        Update
                    </button>
                </div>


                @extends('layouts.master')
        </form>
    </div>
</div>
@endsection