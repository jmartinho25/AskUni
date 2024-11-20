<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\NotificationController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;

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


//Home
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Cards
Route::controller(CardController::class)->group(function () {
    Route::get('/cards', 'list')->name('cards');
    Route::get('/cards/{id}', 'show');
});


// API
Route::controller(CardController::class)->group(function () {
    Route::put('/api/cards', 'create');
    Route::delete('/api/cards/{card_id}', 'delete');
});

Route::controller(ItemController::class)->group(function () {
    Route::put('/api/cards/{card_id}', 'create');
    Route::post('/api/item/{id}', 'update');
    Route::delete('/api/item/{id}', 'delete');
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
});

// Posts

Route::controller(UserController::class)->group(function () {
    Route::get('/api/users/{id}/questions', 'getUserQuestionsAPI');

    Route::get('/api/notifications', 'getNotificationsAPI');
});
// Question Routes (API)
Route::controller(QuestionController::class)->group(function () {
    Route::get('/api/question/{id}', 'getQuestionAPI');
    Route::delete('/api/question/{id}', 'deleteQuestionAPI');
    Route::get('/questions/top', [HomeController::class, 'topQuestions'])->name('questions.top');
});

// Question Routes (Web)

Route::resource('questions', QuestionController::class);
Route::get('/questions/{id}', [QuestionController::class, 'show'])->name('questions.show');

Route::get('questions/{question}/answers/create', [AnswerController::class, 'create'])->name('answers.create');
Route::post('/answers/{question}', [AnswerController::class, 'store'])->name('answers.store');

Route::controller(NotificationController::class)->group(function () {

    Route::put('/api/notifications/{id}', 'markAsReadAPI')->name('notifications.read');
});