<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'book_id'
    ];

    // Получаем книгу
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }
}
