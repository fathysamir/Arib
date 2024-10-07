<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Casts\CustomDateTimeCast;

class Project extends Model
{
    use HasFactory,SoftDeletes;
   
    protected $table = 'projects';
    protected $fillable = [
        'is_active',
        'name',
        
       
    ];
    protected $allowedSorts = [
       
        'created_at',
        'updated_at'
    ];


    
    public function tasks()
    {
        return $this->hasMany(Task::class,'project_id');
    }
    

    
}
