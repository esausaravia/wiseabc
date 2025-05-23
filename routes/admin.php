<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\PayoutController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\AdminController;

/**
 * ADMINISTRADOR
 */
Route::prefix('admin')->name('admin.')->middleware('auth', 'admin')->group(function () {

  Route::get('/', function () {
      return redirect()->route('admin.home');
  });

  Route::get('dashboard', [AdminController::class, 'home'])->name('home');

  Route::get('classroom/createforcurso/{curso}', [ClassroomController::class, 'createForCurso'])->name('classroom.createforcurso');

  Route::get('classroom/createforteacher/{teacher}', [ClassroomController::class, 'createForTeacher'])->name('classroom.createforteacher');

  Route::get('classroom/{id}/assignStudents', [ClassroomController::class, 'assignStudents'])->name('classroom.assignStudents');

  Route::post('classroom/{id}/assignStudents', [ClassroomController::class, 'assignStudents2']);

  Route::get('pagos/profesor/{id}', [PayoutController::class, 'paraprofe'])->name('pagoparaprofe');

  Route::get('student/{student}/assignclass', [StudentController::class, 'assignClassroom'])->name('student.assignclass');

  Route::post('student/{student}/assignclass', [StudentController::class, 'assignClassroom2']);

  Route::resources([
      'billingplans' => \App\Http\Controllers\Admin\BillingPlanController::class,
      'classroom' => ClassroomController::class,
      'cursos' => \App\Http\Controllers\Admin\CursoController::class,
      'pagos' => PayoutController::class,
      'student' => StudentController::class,
      'teacher' => \App\Http\Controllers\Admin\TeacherController::class,
  ]);

});