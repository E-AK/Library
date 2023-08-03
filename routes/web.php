<?php

use Illuminate\Support\Facades\Route;

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

// Маршрут главной страницы
Route::get('/', function() {
    // Проверяем авторизацию пользователя
    if (Auth::check()) {
        // Если пользователь авторизован, перенаправляем на страницу своего профиля
        return redirect(route('profile', ['profileId' => Auth::id()]));
    }

    // Если пользователь не авторизован, перенаправляем на страницу со списком пользователей
    return redirect(route('users'));
})->name('index');

// Маршрут для вывода страницы аутентификации
Route::get('/login', [App\Http\Controllers\LoginController::class, 'index'])->name('login');

// Маршрут для обработки запроса аутентификации
Route::post('/login', [App\Http\Controllers\LoginController::class, 'login']);

// Маршрут для выхода из системы
Route::get('/logout', function () {
    Auth::logout();

    // Перенаправляем на страницу со списком пользователей после выхода из системы
    return redirect(route('users'));
})->name('logout');

// Маршрут для вывода страницы регистрации
Route::get('/registration', [App\Http\Controllers\RegisterController::class, 'index'])->name('registration');

// Маршрут для обработки запроса регистрации
Route::post('/registration', [App\Http\Controllers\RegisterController::class, 'save']);

// Маршрут для вывода списка пользователей
Route::get('/users', [App\Http\Controllers\UsersController::class, 'index'])->name('users');

// Маршрут для вывода информации об одном пользователе по его ID
Route::get('/profile/{profileId}', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');

// Маршрут для изменения доступа к библиотеке
Route::post('/accessLibrary/{profileId}', [App\Http\Controllers\AccessLibraryController::class, 'changeAccess'])->name('accessLibrary.change');

// Маршрут для изменения доступа к книге
Route::post('/accessBook/{bookId}', [App\Http\Controllers\AccessBookController::class, 'changeAccess'])->name('accessBook.change');

// Маршруты для книг
Route::prefix('/book')->name('book.')->group(function () {
    // Маршрут для вывода списка книг в библиотеке пользователя
    Route::get('/books/{profileId}', [App\Http\Controllers\BookController::class, 'getBooks'])->name('getBooks')->middleware('library');

    // Маршрут для вывода информации о книге по ее ID
    Route::get('/book/{bookId}', [App\Http\Controllers\BookController::class, 'getBook'])->name('getBook')->middleware('book');

    // Маршрут для получения списка общедоступных книг пользователя
    Route::get('/books/shared/{profileId}', [App\Http\Controllers\BookController::class, 'getSharedBooks'])->name('getSharedBooks');

    // Маршрут для вывода страницы создания книги
    Route::get('/create', [App\Http\Controllers\BookController::class, 'createPage'])->name('createPage');

    // Маршрут для обработки запроса создания книги
    Route::post('/create', [App\Http\Controllers\BookController::class, 'create'])->name('create');

    // Маршрут для вывода страницы редактирования книги
    Route::get('/update/{bookId}', [App\Http\Controllers\BookController::class, 'updatePage'])->name('updatePage')->middleware('book');

    // Маршрут для обработки запроса редактирования книги
    Route::post('/update/{bookId}', [App\Http\Controllers\BookController::class, 'update'])->name('update')->middleware('book');

    // Маршрут для обработки запроса удаления книги
    Route::post('/delete/{bookId}', [App\Http\Controllers\BookController::class, 'delete'])->name('delete')->middleware('book');
});