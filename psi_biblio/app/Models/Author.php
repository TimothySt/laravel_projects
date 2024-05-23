<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $primaryKey = 'author_id'; // Ustawienie nazwy klucza głównego

    protected $fillable = ['name', 'description'];
    // nane unique
    

    // description nullable



    public function books()
    {
        return $this->belongsToMany(Book::class, 'books_authors', 'author_id', 'title_id');
    }
}
