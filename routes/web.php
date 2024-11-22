<?php
use Illuminate\Support\Facades\Route;



use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\NotificationController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\FeedController;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Root
Route::redirect('/', '/home');

// Home
Route::get('/home', [HomeController::class, 'index'])->name('home');


// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// Profile
Route::controller(UserController::class)->group(function () {
    Route::get('/users/edit-profile', 'editUser')->name('edit-profile');
    Route::put('/users/edit-profile', 'updateUser')->name('update-profile');
    Route::get('/users/{id}', 'show')->name('profile');
});

// Posts
Route::controller(UserController::class)->group(function () {
    Route::get('/api/users/{id}/questions', 'getUserQuestionsAPI');

    Route::get('/api/notifications', 'getNotificationsAPI');
});

// Question Routes (API)
Route::controller(QuestionController::class)->group(function () {
    Route::get('/questions/search', 'search')->name('questions.search');
    Route::get('/api/questions/search', 'searchAPI')->name('api.questions.search');
    Route::get('/api/question/{id}', 'getQuestionAPI');
    Route::delete('/api/question/{id}', 'deleteQuestionAPI');
    Route::get('/questions/top', [HomeController::class, 'topQuestions'])->name('questions.top');
});

// Question Routes (Web)
Route::resource('questions', QuestionController::class);
Route::get('/questions/{question}', [QuestionController::class, 'show'])->name('questions.show');


Route::controller(NotificationController::class)->group(function () {
    Route::put('/api/notifications/mark-all-read', 'markAllReadAPI')->name('notifications.read.all');
    Route::put('/api/notifications/{id}', 'markAsReadAPI')->name('notifications.read');
});

Route::get('/feed', [FeedController::class, 'index'])->name('feed');


Route::get('questions/{question}/answers/create', [AnswerController::class, 'create'])->name('answers.create');
Route::post('questions/{question}/answers', [AnswerController::class, 'store'])->name('answers.store');
Route::get('answers/{answer}/edit', [AnswerController::class, 'edit'])->name('answers.edit');
Route::put('answers/{answer}', [AnswerController::class, 'update'])->name('answers.update');
Route::delete('answers/{answer}', [AnswerController::class, 'destroy'])->name('answers.destroy');
Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
Route::get('/admin/posts', [QuestionController::class, 'index'])->name('posts.index');

Route::middleware('admin')->get('/admin/users', [UserController::class, 'index'])->name('users.index');
Route::middleware('admin')->delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->name('admin.dashboard')
    ->middleware('admin');
