<x-app-layout>
    <div class="max-w-7xl p-6 mx-auto space-y-6">

        {{-- Assignment Details --}}
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-2">📄 {{ $assignment->title }}</h1>
            <p class="text-gray-700 mb-4">{{ $assignment->description }}</p>

            {{-- Template file --}}
            @if($assignment->file_path)
            <a href="{{ asset($assignment->file_path) }}"
                class="inline-block mb-2 px-3 py-1 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200 transition">
                📄 Download Template
            </a>
            @else
            <p class="text-gray-500 italic">No files attached</p>
            @endif

            <div class="flex gap-6 text-sm text-gray-600 mt-2">
                <p>🗓️ Created: {{ $assignment->created_at->format('M d, Y h:i A') }}</p>
                <p>⏰ Due: {{ $assignment->due_date->format('M d, Y h:i A') }}</p>
            </div>
        </div>

        {{-- Submissions & Grades --}}

        @if($submissions->count())
        <form action="{{ route('assignments.grade', [$course->id, $module->id, $assignment->id]) }}" method="POST">
            @csrf

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white shadow rounded-lg overflow-hidden">
                    <thead class="bg-indigo-50 text-left">
                        <tr>
                            <th class="px-4 py-3 font-medium text-gray-700">Student Name</th>
                            <th class="px-4 py-3 font-medium text-gray-700">Uploaded Files</th>
                            <th class="px-4 py-3 font-medium text-gray-700">Grade</th>
                            <th class="px-4 py-3 font-medium text-gray-700">Feedback</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($submissions as $submission)
                        @php $grade = $submission->grade()->where('assignment_id', $submission->assignment_id)->first(); @endphp
                        <tr class="hover:bg-gray-50">
                            {{-- Student Name --}}
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $submission->student->name }}</td>

                            {{-- Uploaded Files --}}
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    @php $files = json_decode($submission->file_path, true) ?? []; @endphp
                                    @forelse($files as $file)
                                    <a href="{{ asset($file) }}" target="_blank"
                                        class="px-2 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 text-sm">
                                        📄 {{ basename($file) }}
                                    </a>
                                    @empty
                                    <span class="text-gray-500 italic text-sm">No submission</span>
                                    @endforelse
                                </div>
                            </td>

                            {{-- Grade --}}
                            <td class="px-4 py-3">
                                @if($grade)
                                <span class="px-3 py-1 bg-gray-100 rounded">{{ $grade->grade }}</span>
                                @else
                                <input type="number" name="grades[{{ $submission->student_id }}]"
                                    value=""
                                    class="border border-gray-300 rounded px-2 py-1 w-full focus:ring-indigo-500 focus:border-indigo-500">
                                @endif
                            </td>

                            {{-- Feedback --}}
                            <td class="px-4 py-3">
                                @if($grade)
                                <span class="px-3 py-1 bg-gray-100 rounded block">{{ $grade->feedback }}</span>
                                @else
                                <textarea name="feedbacks[{{ $submission->student_id }}]"
                                    class="border border-gray-300 rounded px-2 py-1 w-full focus:ring-indigo-500 focus:border-indigo-500"
                                    rows="2"></textarea>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-right">
                                @if(!$grade)
                                <button type="submit" name="student_id" value="{{ $submission->student_id }}"
                                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                                    💾 Save Grade
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>





        </form>
        @else
        <div class="bg-white shadow rounded-lg p-4">
            <p class="text-gray-500 italic">No student submissions yet.</p>
        </div>
        @endif

    </div>
</x-app-layout>