<x-app-layout>

    <div class="py-12 mx-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <h1 class="text-2xl font-bold mb-6">
                Hi, {{ Auth::user()->name }} 👋
            </h1>


            {{-- Upcoming Assignments --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-xl font-bold mb-4">📌 Assignments Due in Next 7 Days</h1>

                @forelse($upcomingAssignments as $assignment)
                <div class="border-b py-3">
                    <a href="{{ route('assignments.show', [
        'course' => $assignment->course_id,
        'module' => $assignment->module_id,
        'assignment' => $assignment->id
    ]) }}" class="font-semibold text-indigo-600 hover:underline">
                        {{ $assignment->title }}
                    </a>
                    <p class="text-sm text-gray-500">
                        Due: {{ \Illuminate\Support\Carbon::parse($assignment->due_date)->format('d M Y, H:i') }}
                    </p>
                </div>
                @empty
                <p class="text-gray-500">🎉 Yay! No assignments due soon.</p>
                @endforelse
            </div>

            {{-- Recently Accessed Courses --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-xl font-bold mb-4">📚 Recently Accessed Courses</h1>

                @if($recentCourses->isEmpty())
                <p class="text-gray-500">No recently accessed courses.</p>
                @else
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    @foreach($recentCourses->take(3) as $course)
                    <a href="{{ route('courses.modules.index', $course->id) }}">
                        <div class="border rounded-lg shadow hover:shadow-lg transition p-4 flex flex-col items-center">
                            <img src="{{ $course->image ?? 'https://via.placeholder.com/150' }}" alt="{{ $course->title }}" class="w-full h-32 object-cover rounded mb-3">
                            <h2 class="text-lg font-semibold text-indigo-600 mb-1">{{ $course->title }}</h2>
                            <p class="text-gray-500 text-sm text-center">{{ $course->description ?? 'No description available' }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif
            </div>



            {{-- Assignment Calendar --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-xl font-bold mb-4">🗓 Assignment Calendar</h1>

                {{-- Weekdays Header --}}
                <div class="grid grid-cols-7 text-center font-semibold mb-2">
                    @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                    <div>{{ $day }}</div>
                    @endforeach
                </div>

                {{-- Calendar Days --}}
                <div class="grid grid-cols-7 gap-2">
                    @foreach($daysInMonth as $date => $assignments)
                    @php
                    $carbonDate = \Carbon\Carbon::parse($date);
                    $isToday = $carbonDate->isToday();
                    $isWeekend = in_array($carbonDate->dayOfWeek, [0,6]);
                    @endphp

                    <div class="border rounded p-2 h-28 flex flex-col justify-start relative transition transform hover:scale-105 hover:shadow-lg 
                        {{ $isToday ? 'bg-indigo-100 border-indigo-400' : ($isWeekend ? 'bg-gray-50' : 'bg-white') }}"
                        title="@if($assignments->count()) {{ $assignments->pluck('title')->join(', ') }} @else No assignments @endif">

                        <span class="font-bold mb-1">{{ $carbonDate->format('d M') }}</span>

                        {{-- Red dot for assignments --}}
                        @if($assignments->count())
                        <span class="absolute top-2 right-2 w-3 h-3 bg-red-500 rounded-full"></span>
                        @endif

                    </div>
                    @endforeach
                </div>
            </div>




        </div>
    </div>


</x-app-layout>