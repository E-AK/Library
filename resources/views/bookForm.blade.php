@extends('layouts.main')

@section('title', $book ? 'Редактирование книги' : 'Создание книги')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <!-- Заголовок карточки: Редактирование книги или Создание книги -->
                <h5 class="card-title">{{ $book ? 'Редактирование книги' : 'Создание книги' }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ $book ? route('book.update', ['bookId' => $book->id]) : route('book.create') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <!-- Поле ввода заголовка книги -->
                        <label for="title">Заголовок</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $book ? $book->title : old('title') }}">
                    </div>
                    <div class="form-group">
                        <!-- Поле ввода текста книги -->
                        <label for="text">Текст</label>
                        <textarea class="form-control" id="text" name="text" rows="25">{{ $book ? $book->text : old('text') }}</textarea>
                    </div>
                    <!-- Кнопка "Сохранить изменения" или "Создать книгу" -->
                    <button type="submit" class="btn btn-primary">{{ $book ? 'Сохранить изменения' : 'Создать книгу' }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
