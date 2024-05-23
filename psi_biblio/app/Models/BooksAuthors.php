<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BooksAuthors extends Model
{
    use HasFactory;

    protected $table = 'books_authors';

    // protected $primaryKey = 'id';
    // Wyłącz domyślne ustawienie klucza głównego
    protected $primaryKey = null;

    protected $fillable = [
        'title_id',
        'author_id',
    ];
    
    // Ustaw indeks unikalny na połączenie title_id i author_id
    protected $unique = [
        'title_id',
        'author_id',
    ];
    public $timestamps = false;
}
