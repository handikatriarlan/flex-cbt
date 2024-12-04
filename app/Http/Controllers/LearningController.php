<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\StudentAnswer;
use App\Models\CourseQuestion;
use Illuminate\Support\Facades\Auth;

class LearningController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $my_courses = $user->courses()->with('category')->orderBy('id', 'DESC')->get();

        foreach ($my_courses as $course) {
            $totalQuestionsCount = $course->questions()->count();

            $answeredQuestionsCount = StudentAnswer::where('user_id', $user->id)
                ->whereHas('question', function ($query) use ($course) {
                    $query->where('course_id', $course->id);
                })->distinct()->count('course_question_id');

            if ($answeredQuestionsCount < $totalQuestionsCount) {
                $firstUnansweredQuestion = CourseQuestion::where('course_id', $course->id)
                    ->whereNotIn('id', function ($query) use ($user) {
                        $query->select('course_question_id')->from('student_answers')
                            ->where('user_id', $user->id);
                    })->orderBy('id', 'ASC')->first();

                $course->nextQuestionId = $firstUnansweredQuestion ? $firstUnansweredQuestion->id : null;
            } else {
                $course->nextQuestionId = null;
            }
        }

        return view('student.courses.index', [
            'my_courses' => $my_courses,
        ]);
    }

    public function learning(Course $course, $question)
    {
        $user = Auth::user();

        $isEnrolled = $user->courses()
            ->where('course_id', $course->id)
            ->exists();

        if (!$isEnrolled) {
            abort(404);
        }

        $currentQuestion = CourseQuestion::where('course_id', $course->id)
            ->where('id', $question)
            ->firstOrFail();

        return view('student.courses.learning', [
            'course' => $course,
            'question' => $currentQuestion,
        ]);
    }

    public function learning_report(Course $course)
    {
        $userId = Auth::id();

        $studentAnswers = StudentAnswer::with('question')
            ->whereHas('question', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })->where('user_id', $userId)
            ->get();

        $totalQuestions = CourseQuestion::where('course_id', $course->id)->count();
        $correctAnswersCount = $studentAnswers
            ->where('answer', 'correct')
            ->count();
        $passed = $correctAnswersCount == $totalQuestions;

        return view('student.courses.learning-report', [
            'course' => $course,
            'studentAnswers' => $studentAnswers,
            'totalQuestions' => $totalQuestions,
            'correctAnswersCount' => $correctAnswersCount,
            'passed' => $passed,
        ]);
    }
}
