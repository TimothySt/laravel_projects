<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Copy extends Model
{
    use HasFactory;

    protected $table = 'copies';
    protected $primaryKey = 'copy_id';
    protected $fillable = [
        'copy_id',
        'title_id',
        'available'
    ];

    // this belong to book.title_id
    public function book()
    {
        return $this->belongsTo(Book::class, 'title_id', 'title_id');
    }
    
    protected $casts = [
        'copy_id' => 'string'
    ];

    // Reguły walidacji
    public static $rules = [
        'copy_id' => 'required|min:10|unique:copies,copy_id',
        'title_id' => 'required|exists:books,title_id',
        'available' => 'required|boolean',
    ];
    public $incrementing = false; // Wyłącz autoinkrementację
}
