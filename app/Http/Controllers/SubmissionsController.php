<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Module;
use App\Models\Submissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionsController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course, Module $module, Assignment $assignment)
    {
        $request->validate([
            'file_path.*' => 'required|mimes:pdf,doc,docx,txt|max:20480'
        ]);

        $submission = Submissions::firstOrNew([
            'assignment_id' => $assignment->id,
            'student_id' => auth()->id()
        ]);

        // If new submission, set relation fields
        if (!$submission->exists) {
            $submission->assignment_id = $assignment->id;
            $submission->student_id = auth()->id();
        }

        $existingFiles = $submission->file_path ? json_decode($submission->file_path, true) : [];
        $newFiles = [];

        if ($request->hasFile('file_path')) {
            foreach ($request->file('file_path') as $file) {
                $originalName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('submissions', $originalName, 'public');
                $newFiles[] = '/storage/' . $path;
            }
        }

        $submission->file_path = json_encode(array_merge($existingFiles, $newFiles));
        $submission->save();

        return back()->with('success', 'Files uploaded successfully!');
    }

    public function destroy(Submissions $submissions, $index)
    {
        $files = json_decode($submissions->file_path, true);

        if (isset($files[$index])) {
            $path = str_replace('/storage/', '', $files[$index]);
            Storage::disk('public')->delete($path);

            unset($files[$index]);
            $files = array_values($files);

            if (empty($files)) {
                $submissions->delete(); 
            } else {
                $submissions->file_path = json_encode($files);
                $submissions->save();
            }
        }

        return back()->with('success', 'File deleted successfully');
    }
}
