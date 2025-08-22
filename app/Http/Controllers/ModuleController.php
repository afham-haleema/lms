<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use App\Models\ModuleResource;
use App\Models\User;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course)
    {
        $modules = Module::all();

        foreach ($modules as $module) {
            if ($module->title === 'Assignments') {
                $module->load(['assignments' => function ($query) use ($course) {
                    $query->where('course_id', $course->id);
                }]);
            } else {
                $module->load(['resources' => function ($query) use ($course) {
                    $query->where('course_id', $course->id);
                }]);
            }
        }

        $participants = User::whereHas('courses', function ($q) use ($course) {
            $q->where('course_id', $course->id);
        })->get();


        return view('modules.index', compact('course', 'modules', 'participants'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:255',
            'resource_type' => 'required|in:pdf,link,video,other',
            'file_path' => 'required|string',
        ]);

        ModuleResource::create($validated);

        return redirect()->back()->with('success', 'Resource added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Module $module)
    {
        return view('modules.edit', compact('module'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ModuleResource $resource)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:255',
            'resource_type' => 'required|in:pdf,link,video,other',
            'file_path' => 'required|string',
        ]);

        $resource->update($validated);

        return redirect()->back()->with('success', 'Resource Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module, $resourceId)
    {
        $resource = ModuleResource::findOrFail($resourceId);
        $resource->delete();

        return redirect()->back()->with('success', 'Resource deleted successfully');
    }
}
