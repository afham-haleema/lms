<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LMS System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="shortcut icon" href="{{ asset('images/lms.png') }}" type="image/png">
    <link rel="icon" href="{{ asset('images/lms.png') }}" type="image/png">
</head>
<body class="bg-gray-100 text-gray-800">

    {{-- Top Navigation --}}
    <nav class="flex justify-between items-center px-6 py-4 shadow-sm bg-white">
        <a href="{{ url('/') }}" class="text-2xl font-bold text-indigo-700">MyLMS</a>

        <div class="space-x-4">
            @guest
                <a href="{{ route('login') }}" class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700 transition">Login</a>
                <a href="{{ route('register') }}" class="px-4 py-2 rounded border border-indigo-600 text-indigo-600 hover:bg-indigo-50 transition">Register</a>
            @else
                @if(Auth::user()->role_id === 2)
                    <a href="{{ route('courses.index') }}" class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700 transition">Go to Courses</a>
                @else
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700 transition">Go to Dashboard</a>
                @endif
            @endguest
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-6 space-y-10">

        {{-- Hero Section --}}
        <section class="bg-indigo-50 rounded-lg p-10 shadow-lg text-center">
            <h1 class="text-4xl font-bold text-indigo-700 mb-4">📚 Welcome to Your LMS</h1>
            <p class="text-gray-700 text-lg mb-6">
                Manage courses, track assignments, submit work, and monitor progress — all in one seamless platform.
            </p>

            @guest
            <a href="{{ route('login') }}" 
               class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition font-semibold">
               Get Started
            </a>
            @endguest
        </section>

        {{-- Features Section --}}
        <section class="grid sm:grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach([
                ['📝 Manage Assignments', 'Create, track, and grade assignments with ease. Students can submit their work, instructors can give feedback instantly.'],
                ['🎯 Track Progress', 'Monitor student performance in real-time. View grades, submissions, and feedback easily.'],
                ['📂 Course Management', 'Organize courses, modules, and content in a structured, easy-to-navigate way.'],
                ['🔔 Notifications', 'Stay informed with instant notifications for assignments, submissions, and announcements.'],
            ] as $feature)
            <div class="bg-white shadow-md rounded-lg p-6 text-center hover:shadow-xl transition transform hover:-translate-y-1">
                <h2 class="text-xl font-semibold text-indigo-700 mb-2">{{ $feature[0] }}</h2>
                <p class="text-gray-600">{{ $feature[1] }}</p>
            </div>
            @endforeach
        </section>

    </main>

</body>
</html>
