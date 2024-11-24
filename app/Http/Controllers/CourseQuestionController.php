<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\CourseQuestion;

class CourseQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Course $course)
    {
        // dd($course);
        $students = $course->students()->orderBy('id', 'DESC')->get();

        return view('admin.questions.create', [
            'course' => $course,
            'students' => $students,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseQuestion $courseQuestion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseQuestion $courseQuestion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseQuestion $courseQuestion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseQuestion $courseQuestion)
    {
        //
    }
}
