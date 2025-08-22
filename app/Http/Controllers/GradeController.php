<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grades=Grade::with('student')->get();
        return view('grades.index',compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('grades.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated=$request->validate([
            'student_id'=>'required|exists:users,id',
            'course_id'=>'required|exists:courses,id',
            'grade'=>'required|integer',
        ]);
        Grade::create($validated);
        return redirect()->route('grades.index')->with('success','Added grade successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade)
    {
        $grade->load('student','course');
        return view('grades.student',compact('grade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        $student=$grade->load('student','course');
        return view('grades.edit',compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade)
    {
        $validated=$request->validate([
            'student_id'=>'required|exists:users,id',
            'course_id'=>'required|exists:courses,id',
            'grade'=>'required|integer',
        ]);
        $grade->update($validated);
        return redirect()->route('grades.index')->with('success','Updated grade successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('grades.index')->with('success','Deleted grade successfully');
    }
}
