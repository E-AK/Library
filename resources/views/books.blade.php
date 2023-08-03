<div class="container mt-4">
    <h1>Библиотека</h1>
    <div class="row">
        @foreach($books as $book)
            <!-- Одна книга будет отображаться в полной ширине колонки -->
            <div class="col-md-12 mb-4">
                <!-- Включение шаблона "bookCard" для отображения информации о книге -->
                @include('templates/bookCard')
            </div>
        @endforeach
    </div>
</div>
