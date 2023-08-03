<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AccessLibrary;

class AccessLibraryController extends Controller
{
    /**
     * Изменение доступа к библиотеке пользователя.
     *
     * @param int $profileId ID профиля пользователя, для которого изменяется доступ к библиотеке
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeAccess($profileId)
    {
        // Проверяем, авторизован ли пользователь
        if (!Auth::check()) {
            return abort(403, 'Вы не авторизованы');
        }

        // Поиск записи о доступе к библиотеке для текущего авторизованного пользователя
        $accessLibrary = AccessLibrary::where('author_id', Auth::id())
            ->where('user_id', $profileId)
            ->first();

        // Если запись о доступе существует, удаляем ее
        if ($accessLibrary) {
            $accessLibrary->delete();
        } else {
            // Если записи о доступе нет, создаем новую запись
            AccessLibrary::create([
                'author_id' => Auth::id(),
                'user_id' => $profileId,
            ]);
        }

        // Перенаправление пользователя обратно на предыдущую страницу
        return redirect()->back();
    }
}
