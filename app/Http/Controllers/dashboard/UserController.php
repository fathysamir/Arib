<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Department;
use Image;
use Str;
use File;

class UserController extends Controller
{//done
    public function index(Request $request)
    {
        $all_users = User::orderBy('id', 'desc');

        if ($request->has('search')) {
            $all_users->where(function ($query) use ($request) {
                $query->where('first_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('phone', 'LIKE', '%' . $request->search . '%');
            });
        }

        $all_users = $all_users->with('department:id,name')->paginate(12);

        $all_users->getCollection()->transform(function ($user) {
            // Add the 'image' key based on some condition
            $user->image = getFirstMediaUrl($user,$user->avatarCollection);;
            
            // Add the 'manager_name' key based on some condition
            if($user->manager_id ){
                $manager=User::where('id',$user->manager_id)->first();
                $user->manager_name =$manager->first_name . ' ' . $manager->last_name;
            }else{
                $user->manager_name = '_';
            }
             

            return $user;
        });
         
        return view('dashboard.users.index',compact('all_users'));

    }

    public function create(){
        $departments=Department::where('is_active',1)->get();
        $roles=Role::all();
        $managers= User::role('Manager')->get();
       
        return view('dashboard.users.create',compact('departments','roles','managers'));
    }

    public function store(Request $request){
        
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:191'],
            'last_name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'salary' => ['required'],
            'manager' => ['nullable', 
            Rule::requiredIf($request->input('role') == '3')],
            'department' => ['required', Rule::in(Department::pluck('id'))],
            'image' => ['required'],
            'phone_number' => ['nullable', 'unique:users,phone', 'numeric'],
            'role' => ['required', Rule::in(Role::pluck('id'))]
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
            
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email'=> $request->email ,
                'phone'=>$request->phone_number,
                'salary'=> $request->salary,
                'password'=>  Hash::make($request->password),
                'manager_id'=>$request->manager?$request->manager:null,
                'department_id'=>$request->department,
                'theme'=>'theme1'
                
            ]);
            $role = Role::where('id',$request->role)->first();
            
            $user->assignRole([$role->id]);
            if($request->file('image')){
                uploadMedia($request->file('image'),$user->avatarCollection,$user);
            }
          return redirect('/users')->with('success', 'User created successfully.');;

    }
 

    public function edit($id){
        $user=User::where('id',$id)->first();
        $departments=Department::where('is_active',1)->get();
        $roles=Role::all();
        $managers= User::role('Manager')->where('id','!=',$id)->get();
        return view('dashboard.users.edit',compact('user','departments','roles','managers'));
    }

    public function update(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:191'],
            'last_name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users,email,'.$id],
            
            'salary' => ['required'],
            'manager' => ['nullable'],
            'department'=>['required' , Rule::in(Department::pluck('id'))],
            'image' => ['nullable'] ,
            'phone_number' => ['nullable', 'unique:users,phone,'.$id, 'numeric'],
            'role'=>['required',Rule::in(Role::pluck('id'))]
            

        ]);

           
            if ($validator->fails()) {
                return Redirect::back()->withInput()->withErrors($validator);
            }
            
            User::where('id',$id)->update([ 'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email'=> $request->email ,
                'phone'=>$request->phone_number,
                'salary'=> $request->salary,
                
                'manager_id'=>$request->manager?$request->manager:null,
                'department_id'=>$request->department]);
            $user=User::find($id);
            $role = Role::where('id',$request->role)->first();
            
            $user->syncRoles([$role]);

            // Save the changes
            $user->save();
            if($request->file('image')){
                if(getFirstMediaUrl($user,$user->avatarCollection)==null){
                    uploadMedia($request->file('image'), $user->avatarCollection, $user);
                }else{
                    deleteMedia($user,$user->avatarCollection);
                    uploadMedia($request->file('image'), $user->avatarCollection, $user);
                }
                
            }
             return redirect('/users')->with('success', 'User updated successfully.');

    }


   

     public function delete($id)
    {
        User::where('id', $id)->delete();
        return redirect('/users')->with('success', 'User deleted successfully.');;
    }
}