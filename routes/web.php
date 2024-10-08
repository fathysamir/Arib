<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\AuthController;
use App\Http\Controllers\dashboard\UserController;
use App\Http\Controllers\dashboard\DepartmentController;
use App\Http\Controllers\dashboard\ProjectController;
use App\Http\Controllers\dashboard\TaskController;


/*
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////dashboard//////////////////////////////////////
Route::get('/rrr', function () {
    dd(env('MAIL_USERNAME'));
});
////////////////////////////////////////////////////////////////////////////////
Route::get('/', function () {
    
    if(!auth()->user()){
        return redirect('/login');
    }else{
        return redirect('/tasks');
    }
});

Route::get('/login', [AuthController::class, 'login_view'])->name('login.view');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::group(['middleware' => ['authenticate']], function () {
    
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/change_theme',[AuthController::class,'change_theme'])->name('change_theme');
    /////////////////////////////////////////
    Route::group(['middleware' => ['admin']], function () {
        Route::any('/users', [UserController::class, 'index'])->name('users'); 
        Route::get('/users/create', [UserController::class, 'create'])->name('add.user');
        Route::post('/users/create', [UserController::class, 'store'])->name('create.user');
        Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('edit.user');
        Route::post('/user/update/{id}', [UserController::class, 'update'])->name('update.user');
        Route::get('/user/delete/{id}', [UserController::class, 'delete'])->name('delete.user');
        Route::get('/fetch-managers', [UserController::class, 'fetch_managers'])->name('fetch.managers');
        //////////////////////////////////////////
        Route::any('/departments', [DepartmentController::class, 'index_departments'])->name('departments'); 
        Route::get('/departments/create', [DepartmentController::class, 'create_department'])->name('add.department');
        Route::post('/departments/create', [DepartmentController::class, 'store_department'])->name('create.department');
        Route::get('/department/edit/{id}', [DepartmentController::class, 'edit_department'])->name('edit.department');
        Route::post('/department/update/{id}', [DepartmentController::class, 'update_department'])->name('update.department');
        Route::get('/department/delete/{id}', [DepartmentController::class, 'delete_department'])->name('delete.department');
        
        /////////////////////////////////////////
        Route::any('/projects', [ProjectController::class, 'index'])->name('projects');
        Route::get('/projects/create', [ProjectController::class, 'create'])->name('add.project');
        Route::post('/projects/create', [ProjectController::class, 'store'])->name('create.project');
        Route::get('/project/edit/{id}', [ProjectController::class, 'edit'])->name('edit.project');
        Route::post('/project/update/{id}', [ProjectController::class, 'update'])->name('update.project');
        Route::get('/project/delete/{id}', [ProjectController::class, 'delete'])->name('delete.project');
    });
    //////////////////////////////////////////
    Route::any('/tasks', [TaskController::class, 'index'])->name('tasks');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('add.task');
    Route::post('/tasks/create', [TaskController::class, 'store'])->name('create.task');
    Route::get('/task/edit/{id}', [TaskController::class, 'edit'])->name('edit.task');
    Route::post('/task/update/{id}', [TaskController::class, 'update'])->name('update.task');
    Route::get('/task/delete/{id}', [TaskController::class, 'delete'])->name('delete.task');

});