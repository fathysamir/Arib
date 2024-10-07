<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Validation\Rule;
use Image;
use Str;
use File;

class TaskController extends ApiController
{
    public function index(Request $request)
    {  
        if(auth()->user()->roles->first()->name=='Admin'){
            $all_tasks = Task::orderBy('id', 'desc');
        }elseif(auth()->user()->roles->first()->name=='Manager'){
            $all_tasks = Task::where('assigned_by_id',auth()->user()->id)->orderBy('id', 'desc');
        }else{
            $all_tasks = auth()->user()->user_tasks();
           
        }
        
        

        if ($request->has('search')) {
           
            $all_tasks->where(function ($query) use ($request) {
                $query->where('title', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->search . '%');
                    
            });
        }

        $all_tasks = $all_tasks->paginate(12);
        return view('dashboard.tasks.index',compact('all_tasks'));

    }

    public function create(){
        $projects=Project::where('is_active','1')->get();
        if(auth()->user()->roles->first()->name=='Admin'){
            $employees = User::whereHas('roles',function($q){
                $q->where('name','Employee');
            })->orderBy('id', 'desc')->get();
        }elseif(auth()->user()->roles->first()->name=='Manager'){
            $employees = user::where('manager_id',auth()->user()->id)->whereHas('roles',function($q){
                $q->where('name','Employee');
            })->orderBy('id', 'desc')->get();
        }
       
        return view('dashboard.tasks.create',compact('projects','employees'));
    }

    public function store(Request $request){

            $validator = Validator::make($request->all(), [
                'title' => ['required', 'string', 'max:191'],
                'description' => ['required', 'string'],
                'project' =>['required',Rule::in(Project::pluck('id'))],
                'status'=>['required'],
                'end_time' => ['nullable'],
                'start_time' => ['nullable'],
                'end_date'=> ['nullable'],
                'start_date'=> ['nullable'],
                'users' => ['required', 'array'],
                'users.*' => ['required', Rule::exists('users', 'id')],

            ]);

           
            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator);
            }
            
            if($request->is_active){
                $is_active="1";
            }else{
                $is_active="0";
            }
            $task=Task::create([
                'assigned_by_id'=>auth()->user()->id,
                'title' => $request->title,
                'is_active' => $is_active,
                'description'=> $request->description,
                'project_id' => $request->project,
                'status'=>$request->status,
                'end_time' => $request->end_time,
                'start_time' => $request->start_time,
                'end_date'=>$request->end_date,
                'start_date'=> $request->start_date

            ]);
            $task->users()->attach($request->users);
        

            return redirect()->route('tasks')
                ->with('success', 'Task created successfully.');
          

    }

    public function edit($id){
        $task=Task::find($id);
        $projects=Project::where('is_active',1)->get();
        if(auth()->user()->roles->first()->name=='Admin'){
            $employees = User::whereHas('roles',function($q){
                $q->where('name','Employee');
            })->orderBy('id', 'desc')->get();
        }elseif(auth()->user()->roles->first()->name=='Manager'){
            $employees = user::where('manager_id',auth()->user()->id)->whereHas('roles',function($q){
                $q->where('name','Employee');
            })->orderBy('id', 'desc')->get();
        }else{
            $employees=$task->users;
        }
        return view('dashboard.tasks.edit',compact('projects','task','employees'));
    }

    public function update(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:191'],
            'description' => ['required', 'string'],
            'project' =>['required',Rule::in(Project::pluck('id'))],
            'status'=>['required'],
            'end_time' => ['nullable'],
            'start_time' => ['nullable'],
            'end_date'=> ['nullable'],
            'start_date'=> ['nullable'],
            'users' => ['required', 'array'],
            'users.*' => ['required', Rule::exists('users', 'id')],

        ]);

       
        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        if($request->is_active){
            $is_active="1";
        }else{
            $is_active="0";
        }
            
        Task::where('id',$id)->update([  'assigned_by_id'=>auth()->user()->id,
                'title' => $request->title,
                'is_active' => $is_active,
                'description'=> $request->description,
                'project_id' => $request->project,
                'status'=>$request->status,
                'end_time' => $request->end_time,
                'start_time' => $request->start_time,
                'end_date'=>$request->end_date,
                'start_date'=> $request->start_date
            ]);
            $task=Task::find($id);
            $task->users()->sync($request->users);
    
            // Redirect back with a success message
            return redirect()->route('tasks')
                ->with('success', 'Task updated successfully.');

    }

    public function delete($id){
        $task = Task::findOrFail($id);
    
        // If no employees are assigned to the department, proceed with deleting the department
        $task->delete();
    
        return redirect('/tasks')->with('success', 'Task deleted successfully.');
    }
}