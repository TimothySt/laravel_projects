<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Copy;

class Book extends Model
{
    use HasFactory;

    protected $primaryKey = 'title_id';

    protected $fillable = [
        'isbn',
        'title',
        'description',
        'published_date',
        'publisher_id',
        'pages',
        'language',
    ];
    public static function rules($id = null)
    {
        return [
            'isbn' => 'required|unique:books,isbn,' . $id,
            // Pozostałe reguły walidacji...
        ];
    }
    public function copies()
    {
        return $this->hasMany(Copy::class, 'title_id', 'title_id');
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'books_authors', 'title_id', 'author_id');
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }

    public function available()
    {
        // policz liczbę egzemplarzy książki z available == true
        $count = $this->copies()->where('available', true)->count();
    }
    public function scopeAvailable($query)
    {
        return $query->withCount(['copies as available_copies_count' => function ($query) {
            $query->where('available', true);
        }]);
    }
}
