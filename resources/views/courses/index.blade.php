<x-app-layout>
    <div class="max-w-7xl px-4 mx-auto py-8 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold mb-6">
            Hi, {{ Auth::user()->name }} 👋
        </h1>

        <form action="{{ route('courses.index') }}" method="get" class="mb-4" onsubmit="event.preventDefault(); fetchCourses();">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                id="live-search"
                class="border border-gray-300 focus:outline-none focus:ring focus:ring-indigo-400 w-full sm:w-1/2 rounded-lg px-4 py-2"
                placeholder="Search courses" />
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 courses-list">
            @forelse ($courses as $course)
            <a href="{{ route('courses.show', $course->id) }}"
                class="block bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden group">

                <div class="overflow-hidden rounded-t-xl">
                    <img src="{{ $course->image }}" alt="Course img" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                </div>

                <div class="p-5">
                    <h2 class="text-gray-900 font-semibold text-xl mb-2 group-hover:text-indigo-600 transition-colors duration-300">
                        {{ $course->title }}
                    </h2>
                    <p class="text-gray-700 text-sm leading-relaxed">{{ Str::limit($course->description, 110) }}</p>
                </div>
            </a>
            @empty
            <p class="text-gray-500 col-span-full">No courses found.</p>
            @endforelse
        </div>
    </div>

    @push('scripts')
    <script>
        const fetchCourses = () => {
            const search = document.getElementById('live-search').value;
            fetch(`{{ route('courses.ajaxSearch') }}?search=${encodeURIComponent(search)}`)
                .then(res => res.text())
                .then(html => {
                    document.querySelector('.courses-list').innerHTML = html;
                });
        };

        const typingInterval = 300;
        let typingTimer;
        document.getElementById('live-search').addEventListener('keyup', () => {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(fetchCourses, typingInterval);
        });
    </script>
    @endpush
</x-app-layout>
