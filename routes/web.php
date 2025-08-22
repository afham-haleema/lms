<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseRegistrationController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubmissionsController;
use App\Http\Controllers\DashboardController;
use App\Models\Module;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('courses', CourseController::class);
    Route::resource('assignments', AssignmentController::class);
    Route::resource('submissions', SubmissionsController::class);
    Route::resource('grades', GradeController::class);

    Route::get('courses/search/ajax', [CourseController::class, 'ajaxSearch'])->name('courses.ajaxSearch');
    Route::get('/register-course', [CourseRegistrationController::class, 'index'])->name('register-course');
    Route::post('/register-course', [CourseRegistrationController::class, 'store'])->name('courses.register');
    Route::get('/courses/{course}/modules', [ModuleController::class, 'index'])->name('courses.modules.index');
    Route::get('/courses/{course}/modules/{module}/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->middleware(['auth'])->name('courses.show');
    Route::post('/courses/{course}/modules/{module}/{assignment}/submit', [SubmissionsController::class, 'store'])->name('assignments.submit');
    Route::delete('/submissions/{submissions}/file/{index}', [SubmissionsController::class, 'destroy'])
        ->name('assignments.submit.delete');

    Route::delete('/modules/{module}/{resource}', [ModuleController::class, 'destroy'])->name('modules.destroy');
    Route::delete('/assignments/{assignment}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');
    Route::post('/module-resources', [ModuleController::class, 'store'])->name('module-resources.store');
    Route::put('/module-resources/{resource}', [ModuleController::class, 'update'])->name('modules.update');
    Route::put('/assignments/{assignment}', [AssignmentController::class, 'update'])->name('assignment.update');
    Route::post('/courses/{course}/modules/{module}/assignments/{assignment}/grade',
    [AssignmentController::class, 'saveGrades'])->name('assignments.grade');


});



require __DIR__ . '/auth.php';
