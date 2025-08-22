<x-app-layout>
    <div class="max-w-7xl p-6 space-y-6">

        {{-- Breadcrumb --}}
        <nav class="flex items-center text-gray-600 text-sm space-x-2">
            <a href="{{ route('courses.show', $course->id) }}" class="hover:underline font-semibold">{{ $course->title }}</a>
            <span class="text-gray-400">›</span>
            <span class="hover:underline font-semibold">{{ $module->title }}</span>
            <span class="text-gray-400">›</span>
            <span class="font-semibold text-indigo-600">{{ $assignment->title }}</span>
        </nav>

        {{-- Assignment Info --}}
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4">📄 {{ $assignment->title }}</h1>
            <div class="grid sm:grid-cols-2 gap-4 text-sm text-gray-700">
                <p><span class="font-semibold">Opened:</span> {{ $assignment->created_at->format('d M Y, H:i') }}</p>
                <p><span class="font-semibold">Due date:</span> {{ $assignment->due_date->format('d M Y, H:i') }}</p>
            </div>
            <div class="mt-3">
                <a href="{{ $assignment->file_path }}" class="text-indigo-600 hover:underline">📥 Download Template</a>
            </div>
        </div>

        {{-- Submission Section --}}
        <div>
            @php
            $existingFiles = $submission ? json_decode($submission->file_path, true) : [];
            @endphp

            {{-- Upload Form only if less than 2 files exist --}}
            @if(count($existingFiles) < 2)
                <div class="bg-gray-50 border p-4 rounded-lg mb-4">
                <form action="{{ route('assignments.submit', [$course->id, $module->id, $assignment->id]) }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    <label class="block mb-2 font-semibold">Upload Files (Max {{ 2 - count($existingFiles) }}):</label>
                    <input type="file" name="file_path[]" multiple class="block w-full mb-2" required>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                        ⬆️ Upload
                    </button>
                </form>
        </div>
        @endif

        {{-- Display Existing Files --}}
        <div class="bg-gray-100 p-5 rounded">
            @if(count($existingFiles) === 0)
            <p class="text-gray-500 italic">No submissions yet.</p>
            @else
            @foreach($existingFiles as $index => $file)
            <div class="flex items-center justify-between mb-2">
                <a href="{{ $file }}" class="text-indigo-600 hover:underline" download>
                    📄 {{ basename($file) }}
                </a>
                <form action="{{ url("/submissions/{$submission->id}/file/{$index}") }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:underline">Delete</button>
                </form>
            </div>
            @endforeach
            @endif
        </div>

        {{-- Submission Info --}}
        @if($submission)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded mb-4">
            <p><span class="font-semibold">Submitted At:</span> {{ $submission->created_at->format('d M Y, H:i') }}</p>
            <p><span class="font-semibold">Graded:</span>
                @if($grade)
                @if($grade->grade !== null)
                Score: {{ $grade->grade }}
                @else
                Not graded yet
                @endif
                @else
                Not graded yet
                @endif
            </p>
            @if($grade && $grade->feedback)
            <p><span class="font-semibold">Feedback:</span> {{ $grade->feedback }}</p>
            @endif
        </div>
        @endif
    </div>
    </div>
</x-app-layout>