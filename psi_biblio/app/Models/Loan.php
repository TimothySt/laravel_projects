<?php

// app/Models/Loan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $primaryKey = 'loan_id';

    protected $fillable = [
        'copy_id',
        'member_id',
        'loan_date',
        'due_date',
        'loan_status_id',
    ];
    protected $casts = [
        'copy_id' => 'string'
    ];


    public function copy()
    {
        return $this->belongsTo(Copy::class, 'copy_id', 'copy_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    public function status()
    {
        return $this->belongsTo(LoanStatus::class, 'loan_status_id', 'loan_status_id');
    }

    public function isOvderue()
    {
        return $this->due_date < now();
    }

}

