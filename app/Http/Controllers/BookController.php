<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\User;

class BookController extends Controller
{
    /**
     * Получение списка книг пользователя.
     *
     * @param int $profileId ID профиля пользователя, для которого получаем список книг
     * @return \Illuminate\View\View
     */
    public function getBooks($profileId)
    {
        // Поиск пользователя по его ID
        $user = User::find($profileId);

        // Получение списка книг пользователя
        $books = $user->books;

        // Передача списка книг в представление и отображение их
        return view('books', ['books' => $books]);
    }

    /**
     * Получение информации о конкретной книге.
     *
     * @param int $bookId ID книги, для которой получаем информацию
     * @return \Illuminate\View\View
     */
    public function getBook($bookId)
    {
        // Проверка, авторизован ли пользователь
        $authenticated = Auth::check();

        // Поиск книги по ее ID
        $book = Book::find($bookId);

        // Передача информации о книге и статусе авторизации
        return view('book', ['book' => $book, 'authenticated' => $authenticated]);
    }

    /**
     * Получение списка общедоступных книг пользователя.
     *
     * @param int $profileId ID профиля пользователя, для которого получаем список общедоступных книг
     * @return \Illuminate\View\View
     */
    public function getSharedBooks($profileId)
    {
        // Поиск пользователя по его ID
        $user = User::find($profileId);

        // Получение списка записей AccessBook
        $accessBooks = $user->sharedBooks;

        // Создание пустого массива для хранения списка книг, на которые пользователь дал доступ
        $sharedBooks = [];

        // Перебор всех записей AccessBook и получение экземпляров книг, связанных с каждой записью
        foreach ($accessBooks as $accessBook) {
            // Получение книги
            $book = $accessBook->book;
            
            // Добавление книги в список sharedBooks
            $sharedBooks[] = $book;
        }

        // Передача списка книг в представление и отображение их
        return view('books', ['books' => $sharedBooks]);
    }

    /**
     * Вывод страницы создания книги.
     *
     * @return \Illuminate\View\View
     */
    public function createPage()
    {
        // Проверка, авторизован ли пользователь
        $authenticated = Auth::check();

        // Передача статуса авторизации и значения null для книги в представление и отображение формы создания книги
        return view('bookForm', ['authenticated' => $authenticated, 'book' => null]);
    }

    /**
     * Создание новой книги.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        // Получение ID авторизованного пользователя
        $userId = Auth::id();

        // Валидация данных из запроса
        $validatedData = $request->validate([
            'title' => 'required|string',
            'text' => 'required|string',
        ]);

        // Создание новой книги в базе данных
        $book = Book::create([
            'user_id' => $userId,
            'title' => $validatedData['title'],
            'text' => $validatedData['text']
        ]);

        // Проверка успешного создания книги
        if ($book) {
            // Перенаправление пользователя на страницу его профиля
            return redirect()->route('profile', ['profileId' => $userId]);
        }

        // Перенаправление пользователя на страницу создания книги с ошибкой, если что-то пошло не так
        return redirect()->route('book.createPage')->withErrors([
            'formError' => 'Ошибка регистрации'
        ]);
    }

    /**
     * Вывод страницы редактирования книги.
     *
     * @param int $bookId ID книги, которую нужно отредактировать
     * @return \Illuminate\View\View
     */
    public function updatePage($bookId)
    {
        // Проверка, авторизован ли пользователь
        $authenticated = Auth::check();

        // Поиск книги по ее ID
        $book = Book::find($bookId);

        // Передача статуса авторизации и информации о книге в представление и отображение формы редактирования книги
        return view('bookForm', ['authenticated' => $authenticated, 'book' => $book]);
    }

    /**
     * Обновление информации о книге.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $bookId ID книги, которую нужно обновить
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $bookId)
    {
        // Получение ID авторизованного пользователя
        $userId = Auth::id();

        // Поиск книги по ее ID
        $book = Book::find($bookId);

        // Валидация данных из запроса
        $validatedData = $request->validate([
            'title' => 'required|string',
            'text' => 'required|string',
        ]);

        // Обновление данных книги с использованием валидированных данных
        $book->title = $validatedData['title'];
        $book->text = $validatedData['text'];

        // Сохранение обновленных данных книги в базе данных
        $book->save();

        // Перенаправление пользователя на страницу его профиля после обновления книги
        return redirect()->route('profile', ['profileId' => $userId]);
    }

    /**
     * Удаление книги.
     *
     * @param int $bookId ID книги, которую нужно удалить
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($bookId)
    {
        // Получение ID авторизованного пользователя
        $userId = Auth::id();

        // Поиск книги по ее ID
        $book = Book::find($bookId);

        // Удаление книги из базы данных
        $book->delete();

        // Перенаправление пользователя на страницу его профиля после удаления книги
        return redirect()->route('profile', ['profileId' => $userId]);
    }
}
