<?php

// app/Models/Role.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $primaryKey = 'role_id';

    protected $fillable = [
        'name',
    ];

    public function members()
    {
        return $this->hasMany(Member::class, 'role_id');
    }

    // finde by name
    public static function findByName($name)
    {
        return static::where('name', $name)->first();
    }
}
