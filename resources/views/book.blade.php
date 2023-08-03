@extends('layouts.main')

@section('title', $book->title)

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Отображение информации о книге -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <!-- Заголовок книги -->
                    <h1 class="card-title">{{ $book->title }}</h1>
                    <!-- Автор книги -->
                    <p class="card-text">Автор: {{ $book->author->lastName . ' ' . $book->author->name . ' ' . $book->author->secondName }}</p>
                    <!-- Текст книги -->
                    <p class="card-text">{{ $book->text }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
