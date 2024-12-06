<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\DislikeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupportController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FeedController;
use App\Models\Role;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\TagController;

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
Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'index')->name('home');
});

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
    Route::delete('/users/{id}', 'destroy')->name('users.destroy.profile');
});

// Notifications
Route::controller(UserController::class)->group(function () {
    Route::get('/api/notifications', 'getNotificationsAPI');
});

// Question Routes (API)
Route::controller(QuestionController::class)->group(function () {
    Route::get('/questions/search', 'search')->name('questions.search');
    Route::get('/api/questions/search', 'searchAPI')->name('api.questions.search');
});

// Question Routes (Web)
Route::resource('questions', QuestionController::class);

// Notifications API
Route::controller(NotificationController::class)->group(function () {
    Route::put('/api/notifications/mark-all-read', 'markAllReadAPI')->name('notifications.read.all');
    Route::put('/api/notifications/{id}', 'markAsReadAPI')->name('notifications.read');
});

// Feed
Route::get('/feed', [FeedController::class, 'index'])->name('feed');

// Answer Routes
Route::controller(AnswerController::class)->group(function () {
    Route::get('/questions/{question}/answers/create', 'create')->name('answers.create');
    Route::post('/questions/{question}/answers', 'store')->name('answers.store');
    Route::get('/answers/{answer}/edit', 'edit')->name('answers.edit');
    Route::put('/answers/{answer}', 'update')->name('answers.update');
    Route::delete('/answers/{answer}', 'destroy')->name('answers.destroy');
});


Route::middleware('auth')->group(function () {
    Route::get('{type}/{id}/comments/create', [CommentController::class, 'create'])->name('comments.create');
    Route::post('{type}/{id}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});


Route::middleware('auth')->group(function () {
    Route::post('posts/{id}/like', [LikeController::class, 'store'])->name('like.store');
    Route::delete('posts/{id}/like', [LikeController::class, 'destroy'])->name('like.destroy');
    Route::post('posts/{id}/dislike', [DislikeController::class, 'store'])->name('dislike.store');
    Route::delete('posts/{id}/dislike', [DislikeController::class, 'destroy'])->name('dislike.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/report/create', [ReportController::class, 'create'])->name('report.create');
    Route::post('/report', [ReportController::class, 'store'])->name('report.store');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function() {
    Route::get('dashboard', [UserController::class, 'index'])->name('admin.dashboard');
    Route::get('unblock-requests', [UserController::class, 'unblockRequests'])->name('admin.unblock.requests');
    Route::get('user/{id}/reports', [UserController::class, 'userReports'])->name('admin.user.reports');
    Route::get('/users/search', [UserController::class, 'searchAPI'])->name('admin.users.search');
    Route::post('/users/unblock/{id}', [UserController::class, 'unblock'])->name('admin.users.unblock');
    Route::post('/reports/resolve/{id}', [UserController::class, 'resolveReport'])->name('admin.reports.resolve');
    Route::post('/users/restore/{id}', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('support-contacts', [SupportController::class, 'index'])->name('admin.support.contacts');
});

// Mail Routes
Route::get('password/reset/{token}', [MailController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [MailController::class, 'reset'])->name('password.update');
Route::get('password/request', [MailController::class, 'showLinkRequestForm'])->name('password.request');
Route::get('/send-email', [MailController::class, 'showLinkRequestForm'])->name('send.email.form');
Route::post('/send', [MailController::class, 'send'])->name('send.email');Route::get('/feedback', function () {
    return view('emails.feedback');
})->name('emails.feedback');

Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

Route::middleware(['auth', 'can:admin,App\Models\User'])->group(function () {
    Route::get('/faq/create', [FaqController::class, 'create'])->name('faq.create');
    Route::post('/faq', [FaqController::class, 'store'])->name('faq.store');
    Route::get('/faq/{id}/edit', [FaqController::class, 'edit'])->name('faq.edit');
    Route::put('/faq/{id}', [FaqController::class, 'update'])->name('faq.update');
    Route::delete('/faq/{id}', [FaqController::class, 'destroy'])->name('faq.destroy');
});

// Tag Routes

Route::controller(TagController::class)->group(function () {
    Route::get('/tags', 'index')->name('tags.index');
    Route::get('/tags/{name}', 'show')->name('tags.show');
});


Route::middleware(['auth'])->group(function () {
    Route::post('/support-questions/{id}/solve', [SupportController::class, 'solve'])->name('support-questions.solve');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/my-support-questions', [SupportController::class, 'mySupportQuestions'])->name('my.support.questions');
    Route::post('/support-questions', [SupportController::class, 'store'])->name('support-questions.store');
    Route::get('/support-questions/create', [SupportController::class, 'create'])->name('support-questions.create');
});

Route::middleware(['auth', 'can:admin,App\Models\User'])->group(function () {
    Route::post('/support-answers', [SupportController::class, 'storeAnswer'])->name('support-answers.store');
});
