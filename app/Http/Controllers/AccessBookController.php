<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AccessBook;

class AccessBookController extends Controller
{
    /**
     * Изменение доступа к книге.
     *
     * @param int $bookId ID книги, для которой меняется доступ
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeAccess($bookId)
    {
        // Проверяем, авторизован ли пользователь
        if (!Auth::check()) {
            return abort(403, 'Вы не авторизованы');
        }

        // Поиск записи о доступе к книге для авторизованного пользователя
        $accessBook = AccessBook::where('author_id', Auth::id())
            ->where('book_id', $bookId)
            ->first();

        // Если запись существует, удаляем ее (закрываем доступ)
        if ($accessBook) {
            $accessBook->delete();
        } else {
            // Если записи нет, то создаем новую запись (открываем доступ)
            AccessBook::create([
                'author_id' => Auth::id(),
                'book_id' => $bookId,
            ]);
        }

        // После изменения доступа, перенаправляем пользователя обратно на предыдущую страницу
        return redirect()->back();
    }
}
