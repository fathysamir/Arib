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
use App\Models\User;
use App\Models\Project;
use Illuminate\Validation\Rule;
use Image;
use Str;
use File;

class ProjectController extends ApiController
{
    public function index(Request $request)
    {  
        $all_projects = Project::orderBy('id', 'desc');

        if ($request->has('search')) {
            $all_projects->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $all_projects = $all_projects->paginate(10);
        return view('dashboard.projects.index',compact('all_projects'));

    }

    public function create(){
        return view('dashboard.projects.create');
    }

    public function store(Request $request){

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:191'],

            ]);

           
            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator);
            }
            
            if($request->is_active){
                $is_active="1";
            }else{
                $is_active="0";
            }
            Project::create([
                'name' => $request->name,
                'is_active' => $is_active,

            ]);
           
          return redirect('/projects')->with('success', 'Project created successfully.');;

    }

    public function edit($id){
        $project=Project::where('id',$id)->first();
        return view('dashboard.projects.edit',compact('project'));
    }

    public function update(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:191'],

        ]);

       
        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        if($request->is_active){
            $is_active="1";
        }else{
            $is_active="0";
        }
            
        Project::where('id',$id)->update([ 'name' => $request->name,
            'is_active' => $is_active,
            ]);
        return redirect('/projects')->with('success', 'Project updated successfully.');;

    }

    public function delete($id){
        $project = Project::findOrFail($id);
    
        // If no employees are assigned to the department, proceed with deleting the department
        $project->delete();
    
        return redirect('/projects')->with('success', 'Project deleted successfully.');
    }
}