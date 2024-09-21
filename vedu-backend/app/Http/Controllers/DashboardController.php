<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\CourseInstructor;
use App\Models\CourseStudent;

class DashboardController extends Controller
{
    public function getDashboardStats()
    {
        $totalUsers = User::count();

        $totalInstructors = CourseInstructor::distinct('instructor_id')->count('instructor_id');
        $totalStudents = CourseStudent::distinct('student_id')->count('student_id');

        $totalClasses = Course::count();

        $avgStudentsPerClass = $totalClasses > 0 ? CourseStudent::count() / $totalClasses : 0;

        $avgInstructorsPerClass = $totalClasses > 0 ? CourseInstructor::count() / $totalClasses : 0;

        return response()->json([
            'total_users' => $totalUsers,
            'total_instructors' => $totalInstructors,
            'total_students' => $totalStudents,
            'total_classes' => $totalClasses,
            'avg_students_per_class' => $avgStudentsPerClass,
            'avg_instructors_per_class' => $avgInstructorsPerClass,
        ]);
    }
}
