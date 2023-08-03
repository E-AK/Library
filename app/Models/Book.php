<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'text'
    ];

    // Получаем автора книги
    public function author()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    // Получаем запись о доступе к книге
    public function access()
    {
        return $this->hasOne(AccessBook::class, 'book_id');
    }
}
