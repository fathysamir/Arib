<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\CustomDateTimeCast;
use Illuminate\Database\Eloquent\SoftDeletes;
class Department extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'departments';
    protected $fillable = [
        'name',
        'is_active',
        
    ];

    protected $allowedSorts = [
       
        'created_at',
        'updated_at'
    ];

    protected $hidden = ['deleted_at'];

    public function users()
    {
        return $this->hasMany(User::class,'department_id');
    }
    
}
