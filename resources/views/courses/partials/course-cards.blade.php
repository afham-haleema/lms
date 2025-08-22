@forelse ($courses as $course)
<a href="{{ route('courses.show', $course->id) }}"
    class="block bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden group">

    <div class="overflow-hidden rounded-t-xl">
        <img src="{{ $course->image }}" alt="Course img"
            class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
    </div>

    <div class="p-5">
        <h2
            class="text-gray-900 font-semibold text-xl mb-2 group-hover:text-indigo-600 transition-colors duration-300">
            {{ $course->title }}
        </h2>
        <p class="text-gray-700 text-sm leading-relaxed">{{ Str::limit($course->description, 110) }}</p>
    </div>
</a>
@empty
<p class="text-gray-500 col-span-full">No courses found.</p>
@endforelse
