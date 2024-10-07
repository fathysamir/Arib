<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\CustomDateTimeCast;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory,SoftDeletes;
   
    protected $table = 'tasks';
    protected $fillable = [
        'is_active',
        'title',
        'description',
        'project_id',
        'status',
        'assigned_by_id',
        'end_time',
        'start_time',
        'end_date',
        'start_date'
        
       
    ];
    protected $allowedSorts = [
       
        'created_at',
        'updated_at'
    ];


    
    public function assigned_by()
    {
        return $this->belongsTo(User::class, 'assigned_by_id', 'id');
    }
    
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function users(){
        return $this->belongsToMany(User::class,'tasks_users','task_id','assigned_to_id');
    }
}
