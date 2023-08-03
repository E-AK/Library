@extends('layouts.main')

@section('title', 'Пользователь')

@section('content')
    <div class="container mt-4">
        <!-- Карточка с информацией о пользователе -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title">{{ 'Профиль пользователя' }}</h5>
            </div>

            <!-- Вывод информации о пользователе -->
            <div class="card-body" id="user" data-id="{{ $userPage->id }}">
                <p class="card-text">{{ 'Фамилия: ' . $userPage->lastName }}</p>
                <p class="card-text">{{ 'Имя: ' .  $userPage->name }}</p>
                <p class="card-text">{{ 'Отчество: ' . $userPage->secondName }}</p>
                <p class="card-text">{{ 'День рождения: ' . $userPage->birthday }}</p>

                <!-- Кнопка для изменения доступа к библиотеке -->
                @if(Auth::check() && Auth::id() != $userPage->id)
                    <form method="POST" action="{{ route('accessLibrary.change', ['profileId' => $userPage->id]) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">{{ $accessLibrary ? 'Отключить доступ к библиотеке' : 'Дать доступ к библиотеке' }}</button>
                    </form>
                @endif

                <!-- Ссылка на создание -->
                @if(Auth::check() && Auth::id() == $userPage->id)
                    <a href="{{ route('book.createPage') }}">Создать книгу</a>
                @endif
            </div>
        </div>

        <!-- Контейнер для вывода списка книг -->
        <div id="books"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const user = $('#user');
            const userPageId = user.data('id');

            // Функция для загрузки списка книг при загрузке страницы
            function loadBooks() {
                $.ajax({
                    url: '/book/books/' + userPageId,
                    type: 'GET',
                    success: function(response) {
                        // Вставляем полученный HTML-код внутрь контейнера #books
                        $('#books').html(response);
                    },
                    error: function(error) {
                        // В случае ошибки 403, выводим сообщение об ошибке
                        if (error.status === 403) {
                            $.ajax({
                                url: '/book/books/shared/' + userPageId,
                                type: 'GET',
                                success: function(response) {
                                    // Вставляем полученный HTML-код внутрь контейнера #books
                                    $('#books').html(response);
                                },
                                error: function(error) {
                                    // В случае ошибки 403, выводим сообщение об ошибке
                                    if (error.status === 403) {
                                        $('#books').html('<p>Пользователь не предоставил вам доступ к своей библиотеке</p>');
                                    } else {
                                        console.error(error);
                                    }
                                }
                            })
                        } else {
                            console.error(error);
                        }
                    }
                });
            }

            loadBooks();
        });
    </script>
@stop
