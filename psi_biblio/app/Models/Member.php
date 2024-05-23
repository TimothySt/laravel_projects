<?php

// app/Models/Member.php

// app/Models/Member.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;


class Member extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'member_id';

    protected $fillable = [
        'role_id',// TODO czy może powinno być w hidden?
        'first_name',
        'last_name',
        'email',
        'tel',
        'address',
        'password',
        // 'email_verified_at',
        // 'remember_token',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function rules($memberId = null) // TODO BORKED
    {
        $uniqueEmailRule = Rule::unique('members', 'email')->ignore($memberId, 'member_id');
        $uniqueTelRule = Rule::unique('members', 'tel')->ignore($memberId, 'member_id');
    
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', $uniqueEmailRule],
            'tel' => ['required', 'string','regex:/^(\+\d{1,15}|\d{1,15})$/', $uniqueTelRule],
            'address' => 'required|string|max:255',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }
    
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function hasRole($role_name)
    {
        return $this->role->name == $role_name;
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($query) use ($searchTerm) {
            $query->where('first_name', 'ilike', '%' . $searchTerm . '%')
                ->orWhere('last_name', 'ilike', '%' . $searchTerm . '%')
                ->orWhere('email', 'ilike', '%' . $searchTerm . '%')
                ->orWhere('tel', 'ilike', '%' . $searchTerm . '%');
        });
    }


}
