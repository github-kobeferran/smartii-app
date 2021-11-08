<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Models\Member;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    
    /** 
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    // protected $appends = [
    //     'profile_photo_url',
    // ];


    public function member(){
        return $this->hasOne(Member::class, 'user_id', 'id');
    }

    public function hasRole($role)
    {
        return User::where('user_type', $role)->get();
    }

    public function isAdmin(){
        if (Auth::user()->user_type === 'admin')
            return true;
        else
            false;
    }

    public function isStudent(){
        if (Auth::user()->user_type === 'student')
            return true;
        else
            false;
    }
    public function isFaculty(){
        if (Auth::user()->user_type === 'faculty')
            return true;
        else
            false;
    }
    public function isApplicant(){
        if (Auth::user()->user_type === 'applicant')
            return true;
        else
            false;
    }

    
}
