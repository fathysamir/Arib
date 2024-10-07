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
use App\Models\Department;
use Illuminate\Validation\Rule;
use Image;
use Str;
use File;

class DepartmentController extends ApiController
{
    public function index_departments(Request $request)
    {  
        $all_departments = Department::select('departments.*')
            ->selectRaw('COUNT(users.id) as employee_count')
            ->selectRaw('SUM(users.salary) as total_salary')
            ->leftJoin('users', 'departments.id', '=', 'users.department_id')
            ->groupBy('departments.id')
            ->orderBy('departments.id', 'desc');

        if ($request->has('search')) {
            $all_departments->where('departments.name', 'LIKE', '%' . $request->search . '%');
        }

        $all_departments = $all_departments->paginate(10);
        return view('dashboard.departments.index',compact('all_departments'));

    }

    public function create_department(){
        return view('dashboard.departments.create');
    }

    public function store_department(Request $request){

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:191'],

            ]);

           
            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator);
            }
            
            if($request->is_active){
                $is_active=1;
            }else{
                $is_active=0;
            }
            Department::create([
                'name' => $request->name,
                'is_active' => $is_active,

            ]);
           
          return redirect('/departments')->with('success', 'Department created successfully.');;

    }

    public function edit_department($id){
        $department=Department::where('id',$id)->first();
        return view('dashboard.departments.edit',compact('department'));
    }

    public function update_department(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:191'],

        ]);

       
        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        if($request->is_active){
            $is_active=1;
        }else{
            $is_active=0;
        }
            
        Department::where('id',$id)->update([ 'name' => $request->name,
            'is_active' => $is_active,
            ]);
        return redirect('/departments')->with('success', 'Department updated successfully.');;

    }

    public function delete_department($id){
        $department = Department::findOrFail($id);

        // Check if the department has any employees
        if ($department->users->isNotEmpty()) {
            return back()->with('error', 'Cannot delete department with assigned employees.');
        }
    
        // If no employees are assigned to the department, proceed with deleting the department
        $department->delete();
    
        return redirect('/departments')->with('success', 'Department deleted successfully.');
    }
}