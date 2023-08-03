<div class="col-md-12 mb-4">
    <div class="card">
        <div class="card-body d-flex justify-content-between align-items-center">
            <!-- Заголовок книги с ссылкой на детали книги -->
            <a href="{{ route('book.getBook', ['bookId' => $book->id]) }}"><h5 class="card-title">{{ $book->title }}</h5></a>
            
            <!-- Проверка, является ли текущий пользователь владельцем книги -->
            @if(Auth::id() == $book->user_id)
                <!-- Кнопки для редактирования, удаления и изменения доступности книги -->
                <div>
                    <a href="{{route('book.updatePage', ['bookId' => $book->id])}}" class="btn btn-primary mr-2"><i class="fas fa-edit"></i> Редактировать</a>
                    <form method="POST" action="{{ route('book.delete', ['bookId' => $book->id]) }}">
                        @csrf
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Удалить</button>
                    </form>
                    <form method="POST" action="{{ route('accessBook.change', ['bookId' => $book->id]) }}">
                        @csrf
                        <button type="submit" class="btn btn-info ml-2">
                            {{ $book->access ? 'Закрыть доступ к книге' : 'Книга по ссылке доступна для всех' }}
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
