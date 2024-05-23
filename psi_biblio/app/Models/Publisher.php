<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    use HasFactory;

    protected $primaryKey = 'publisher_id'; // Ustawienie nazwy klucza głównego

    protected $fillable = ['name', 'description'];

    public function books()
    {
        return $this->hasMany(Book::class, 'publisher_id');
    }
}
