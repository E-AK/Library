<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AccessLibrary;

class CheckLibraryAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Получаем ID текущего авторизованного пользователя
        $authUserId = Auth::id();

        // Получаем ID профиля из маршрута запроса
        $profileId = $request->route('profileId');

        // Находим пользователя по ID профиля
        try {
            $user = User::findOrFail($profileId);
        } catch (NotFoundHttpException $e) {
            return abort(404, 'Пользователь не найден');
        }

        // Проверяем, есть ли у текущего пользователя доступ к библиотеке
        $access = AccessLibrary::where('user_id', $authUserId)->first();

        // Проверяем условия для доступа к библиотеке профиля
        if (!$authUserId || (!$access && $authUserId != $profileId)) {
            return abort(403, 'У вас нет доступа к библиотеке этого пользователя');
        }

        // Если все условия выполнены, продолжаем выполнение следующего middleware
        return $next($request);
    }
}
