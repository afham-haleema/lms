<x-app-layout :hideNav="true">
  <div class="max-w-4xl mx-auto px-6 py-8" x-data="{ selected: [], max: 6, min: 4, warning: '' }">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-1">Register Courses</h1>
    <p class="text-red-500 text-sm mb-6">Select between <strong>4</strong> and <strong>6</strong> courses</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($courses as $course)
        <div
          class="cursor-pointer rounded-lg border-2 p-5 shadow-sm transition-transform duration-200 ease-in-out
            hover:scale-105 hover:shadow-lg
            "
          :class="selected.includes({{ $course->id }}) 
            ? 'border-red-500 bg-red-50 text-red-700 font-semibold' 
            : 'border-gray-300 bg-white text-gray-800'"
          @click="
            if (selected.includes({{ $course->id }})) {
              selected = selected.filter(id => id !== {{ $course->id }});
              warning = '';
            } else {
              if (selected.length < max) {
                selected.push({{ $course->id }});
                warning = '';
              } else {
                warning = 'You can only select up to ' + max + ' courses';
              }
            }
          "
        >
          <h2 class="text-xl font-semibold mb-2">{{ $course->title }}</h2>
          <p class="text-gray-600 text-sm line-clamp-3">{{ $course->description ?? 'No description provided.' }}</p>
        </div>
      @endforeach
    </div>

    <p class="text-yellow-600 mt-4 min-h-[1.5rem]" x-text="warning"></p>

    <form action="{{ route('courses.register') }}" method="POST" class="mt-6">
      @csrf
      <template x-for="id in selected" :key="id">
        <input type="hidden" name="courses[]" :value="id" />
      </template>
      <button
        type="submit"
        class="w-full sm:w-auto bg-red-600 hover:bg-red-700 disabled:bg-gray-400 text-white font-semibold py-3 px-6 rounded-lg transition-colors"
        :disabled="selected.length < min || selected.length > max"
      >
        Save Selection
      </button>
      <p class="text-gray-500 mt-3 text-center sm:text-left">
        Selected: <span x-text="selected.length"></span> / <span x-text="max"></span>
      </p>
    </form>
  </div>
</x-app-layout>
