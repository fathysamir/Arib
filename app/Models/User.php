<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $avatarCollection = 'avatar-image';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'salary',
        'phone',
        'password',
        'device_token',
        'manager_id',
        'theme',
        'department_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class,'assigned_by_id');
    }
    
    public function user_tasks(){
        return $this->belongsToMany(Task::class,'tasks_users','assigned_to_id','task_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}
