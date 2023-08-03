<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AccessLibrary;

class ProfileController extends Controller
{
    /**
     * Метод "index".
     *
     * Этот метод отображает информацию о пользователе и его комментарии.
     * Если пользователь не аутентифицирован, то некоторые элементы страницы могут быть скрыты или недоступны.
     *
     * @param int $profileId - ID пользователя, информацию о котором нужно отобразить
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index($profileId)
    {
        // Проверяем аутентификацию текущего пользователя
        $authenticated = Auth::check();

        // Проверяем, если 'id' не передан в строке запроса или задан некорректно, возвращаем код ошибки 404
        if (!is_numeric($profileId)) {
            return abort(404);
        }

        // Находим пользователя с заданным ID в базе данных
        $userPage = User::where('id', $profileId)->first();

        // Проверяем, существует ли пользователь с заданным ID
        if (!$userPage) {
            return abort(404);
        }

        $accessLibrary = AccessLibrary::where('author_id', Auth::id())
            ->where('user_id', $profileId)
            ->first();

        // Если пользователь с заданным ID найден, отображаем страницу с информацией о пользователе и его комментариями
        return view('profile', [
            'authenticated' => $authenticated,
            'userPage' => $userPage,
            'accessLibrary' => $accessLibrary
        ]);
    }
}
