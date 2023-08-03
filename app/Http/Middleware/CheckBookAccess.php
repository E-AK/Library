<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\AccessBook;

class CheckBookAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Получаем текущего аутентифицированного пользователя.
        $authUserId = Auth::id();

        // Получаем идентификатор книги из запроса.
        $bookId = $request->route('bookId');

        // Используем метод findOrFail() для получения книги.
        // Если книга не найдена, будет выброшено исключение NotFoundHttpException.
        try {
            $book = Book::findOrFail($bookId);
        } catch (NotFoundHttpException $e) {
            return abort(404, 'Книга не найдена');
        }

        // Проверяем, имеет ли пользователь доступ к этой книге.
        // Если уникального индекса на полях user_id и book_id нет, используем метод first() для получения одной записи.
        $access = AccessBook::where('book_id', $bookId)->first();

        // Проверяем наличие аутентифицированного пользователя и доступа к книге.
        if (!$authUserId || (!$access && $authUserId != $book->user_id)) {
            return abort(403, 'У вас нет доступа к книге');
        }

        // Если все проверки прошли успешно, передаем управление следующему Middleware.
        return $next($request);
    }
}
