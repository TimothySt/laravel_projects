<?php

// app/Models/LoanStatus.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanStatus extends Model
{
    use HasFactory;

    protected $table = 'loan_status';
    protected $primaryKey = 'loan_status_id';

    protected $fillable = [
        'status',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class, 'loan_status_id', 'loan_status_id');
    }

    public static function findByName($status)
    {
        return static::where('status', $status)->first();
    }
}
