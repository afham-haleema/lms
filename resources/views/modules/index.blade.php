<x-app-layout>
    <div class="max-w-7xl p-6 mx-auto space-y-6"
        x-data="{ tab: 'modules', openDialog: false, modalModuleId: null, modalModuleTitle: '' ,isAssignment:false, editResourceId:'',openEditDialog:false,editResourceType:'',editFilePath:'', editTitle:'', editDueDate:'', editdescription:''}">

        <!-- Page Title -->
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6">{{ $course->title }}</h1>

        <!-- Tabs -->
        <div class="flex space-x-4 border-b border-gray-200 mb-6">
            <button @click="tab = 'modules'"
                :class="tab === 'modules' ? 'border-indigo-500 text-indigo-600 font-semibold' : 'text-gray-600 hover:text-indigo-500'"
                class="pb-2 border-b-2 focus:outline-none">Modules</button>
            <button @click="tab = 'participants'"
                :class="tab === 'participants' ? 'border-indigo-500 text-indigo-600 font-semibold' : 'text-gray-600 hover:text-indigo-500'"
                class="pb-2 border-b-2 focus:outline-none">Participants</button>
        </div>

        <!-- Modules Tab -->
        <div x-show="tab === 'modules'" x-transition>
            @foreach ($modules as $module)
            <div class="border border-gray-300 rounded-xl shadow-lg bg-white mb-4" x-data="{ open: true }">

                <!-- Module Header -->
                <div class="flex justify-between items-center px-6 py-4 bg-indigo-50 rounded-t-xl">
                    <button @click="open = !open" class="flex-1 text-left font-semibold text-indigo-700">
                        {{ $module->title }}
                    </button>

                    <!-- Plus button for adding resource -->
                    @if(auth()->user()->role_id == 2)
                    <button @click="openDialog = true; modalModuleId = {{ $module->id }}; modalModuleTitle = '{{ $module->title }}'; isAssignment = {{ $module->title === 'Assignments' ? 'true' : 'false' }}===true"
                        class="text-green-500 font-bold text-xl ml-2" title="Add resource">➕</button>
                    @endif

                    <!-- Chevron -->
                    <svg :class="{ 'rotate-180': open }" class="w-6 h-6 text-indigo-500 transition-transform duration-300"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                <!-- Module Content -->
                <div x-show="open" x-transition class="px-6 py-4 border-t border-indigo-200">

                    <!-- Assignments -->
                    @if($module->title === 'Assignments')
                    @if($module->assignments->count() > 0)
                    <ul class="space-y-3">
                        @foreach($module->assignments as $assignment)
                        <li class="flex items-center justify-between">
                            <a href="{{ route('assignments.show', [$course->id, $module->id, $assignment->id]) }}"
                                class="text-indigo-600 font-medium">
                                📄 {{ $assignment->title }}
                            </a>
                            <div class="flex gap-4">
                                @if(Auth::user()->role_id == 2)
                                <form action="{{ route('assignments.destroy', $assignment->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline"
                                        onclick="return confirm('Delete this assignment?')">Delete</button>
                                </form>

                                <button
                                    class="text-blue-500 hover:underline"
                                    @click="openEditDialog = true;
            isAssignment = true;
            modalModuleId = {{ $module->id }};
            editResourceId = {{ $assignment->id }};
            editTitle = '{{ $assignment->title }}';
            editdescription = '{{ $assignment->description }}';
            editDueDate = '{{ $assignment->due_date }}';">
                                    Edit
                                </button>

                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-gray-500 italic">No assignments available for this course.</p>
                    @endif

                    <!-- Module Resources -->
                    @else
                    @if($module->resources->count() > 0)
                    <ul class="space-y-3">
                        @foreach($module->resources as $resource)
                        @php $isLink = str_starts_with($resource->file_path, 'http'); @endphp
                        <li class="flex items-center justify-between">
                            @if($isLink)
                            <a href="{{ $resource->file_path }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                {{ $resource->title }}
                            </a>
                            @else
                            <span class="text-gray-700 italic">No valid link for {{ $resource->title }}</span>
                            @endif

                            <div class="flex gap-4">
                                @if(Auth::user()->role_id == 2)
                                <form action="{{ route('modules.destroy', [$module->id, $resource->id]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline"
                                        onclick="return confirm('Are you sure you want to delete this resource?')">
                                        Delete
                                    </button>
                                </form>
                                <button
                                    class="text-blue-500 hover:underline"
                                    @click="openEditDialog = true;
            isAssignment = false;
            modalModuleId = {{ $module->id }};
            editResourceId = {{ $resource->id }};
            editTitle = '{{ $resource->title }}';
            editFilePath = '{{ $resource->file_path }}';
            editResourceType = '{{ $resource->resource_type }}';">
                                    Edit
                                </button>

                                @endif
                            </div>


                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-gray-500 italic">No resources available for this module.</p>
                    @endif
                    @endif

                </div>
            </div>
            @endforeach

            <!-- Add Resource Modal -->
            <div x-show="openDialog" x-cloak
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded w-96">
                    <h2 class="text-lg font-bold mb-4">Add Resource to <span x-text="modalModuleTitle"></span></h2>
                    <form :action="isAssignment ? '{{ route('assignments.store') }}' : '{{ route('module-resources.store') }}'" method="POST">
                        @csrf
                        <input type="hidden" name="module_id" :value="modalModuleId">
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        <input type="text" name="title" placeholder="Title" class="border p-2 w-full my-2" required>
                        <template x-if="isAssignment">
                            <div>
                                <div class="mb-2">
                                    <textarea name="description" placeholder="Assignment Description" class="border p-2 w-full" required></textarea>
                                </div>
                                <div class="mb-2">
                                    <label class="block mb-1 font-medium">Due Date & Time</label>
                                    <input type="datetime-local" name="due_date" class="border p-2 w-full" required>
                                </div>
                            </div>
                        </template>
                        <template x-if="!isAssignment">
                            <select name="resource_type" class="border p-2 w-full my-2" required>
                                <option value="pdf">PDF</option>
                                <option value="link">Link</option>
                                <option value="video">Video</option>
                                <option value="other">Other</option>
                            </select>
                        </template>

                        <input type="text" name="file_path" placeholder="File URL / Path" class="border p-2 w-full my-2" required>
                        <div class="flex justify-end space-x-2 mt-4">
                            <button type="button" @click="openDialog=false" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded" x-text="isAssignment ? 'Add Assignment' : 'Add Resource'"></button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit Dialog -->
            <div x-show="openEditDialog" x-cloak
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded w-96">
                    <h2 class="text-lg font-bold mb-4" x-text="isAssignment ? 'Edit Assignment' : 'Edit Resource'"></h2>

                    <form
                        x-bind:action="isAssignment 
    ? '{{ url('assignments') }}/' + editResourceId 
    : '{{ url('module-resources') }}/' + editResourceId"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="module_id" :value="modalModuleId">
                        <input type="hidden" name="course_id" value="{{ $course->id }}">

                        <!-- Title -->
                        <input type="text" name="title" class="border p-2 w-full my-2" x-model="editTitle" required>

                        <!-- Assignment fields -->
                        <template x-if="isAssignment">
                            <div>
                                <div class="mb-2">
                                    <textarea name="description" class="border p-2 w-full" x-model="editdescription" required></textarea>
                                </div>
                                <div class="mb-2">
                                    <label class="block mb-1 font-medium">Due Date & Time</label>
                                    <input type="datetime-local" name="due_date" class="border p-2 w-full" x-model="editDueDate" required>

                                </div>
                            </div>
                        </template>

                        <!-- Resource fields -->
                        <template x-if="!isAssignment">
                            <select name="resource_type" class="border p-2 w-full my-2" x-model="editResourceType" required>
                                <option value="pdf">PDF</option>
                                <option value="link">Link</option>
                                <option value="video">Video</option>
                                <option value="other">Other</option>
                            </select>
                        </template>

                        <!-- File Path -->
                        <input type="text" name="file_path" class="border p-2 w-full my-2" x-model="editFilePath" required>

                        <div class="flex justify-end space-x-2 mt-4">
                            <button type="button" @click="openEditDialog = false" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <!-- Participants Tab -->
        <div x-show="tab === 'participants'" x-transition>
            <div class="border border-gray-300 rounded-xl shadow-lg bg-white p-6">
                @if($participants->count() > 0)
                <ul class="space-y-3">
                    @foreach($participants as $participant)
                    <li class="flex items-center space-x-3">
                        <span class="w-8 h-8 flex items-center justify-center rounded-full bg-indigo-500 text-white font-bold">
                            {{ strtoupper(substr($participant->name, 0, 1)) }}
                        </span>
                        <span class="text-gray-800 font-medium">{{ $participant->name }}</span>
                        <span class="text-gray-500 text-sm">({{ $participant->email }})</span>
                        @if($participant->role_id == 2)
                        <span class="ml-2 text-red-500 font-semibold">(Instructor)</span>
                        @endif
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-gray-500 italic">No participants enrolled in this course.</p>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>