@extends('dashboard.layout.app')
@section('title', 'Dashboard - create task')
@section('content')	
    <div class="content-wrapper">
        <div class="container-fluid">

            <div class="row mt-3">
                <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body">
                      <div class="card-title">Create New Task</div>
                      <hr>
                       <form method="post" action="{{route('create.task')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title" >Title</label>
                        
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
                            @if ($errors->has('title'))
                                <p class="text-error more-info-err" style="color: red;">
                                    {{ $errors->first('title') }}
                                </p>
                            @endif
                        
                        </div>
                        <div class="form-group">
                            <label for="description" >Description</label>
                           
                            <textarea class="form-control textarea" id="description" name="description">{{ old('description') }}</textarea>
                            @if ($errors->has('description'))
                                <p class="text-error more-info-err" style="color: red;">
                                    {{ $errors->first('description') }}
                                </p>
                            @endif
                            
                        </div>
                        <div class="form-group">
                            <label for="project" >Project</label>
                           
                            <select class="form-control" id="project" name="project">
                                <option value="">Select Project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('project'))
                                <p class="text-error more-info-err" style="color: red;">
                                    {{ $errors->first('project') }}
                                </p>
                            @endif
                            
                        </div>
                        <div class="form-group">
                            <label for="status" >Status</label>
                        
                            <select class="form-control" id="status" name="status">
                                <option value="">Select Status</option>
                                <option value="not started" {{ old('status') == 'not started' ? 'selected' : '' }}>Not Started</option>
                                <option value="working on it" {{ old('status') == 'working on it' ? 'selected' : '' }}>Working On It</option>
                                <option value="stuck" {{ old('status') == 'stuck' ? 'selected' : '' }}>Stuck</option>
                                <option value="on hold" {{ old('status') == 'on hold' ? 'selected' : '' }}>On Hold</option>
                                <option value="under review" {{ old('status') == 'under review' ? 'selected' : '' }}>Under Review</option>
                                <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>Done</option>
                            </select>
                            @if ($errors->has('status'))
                                <p class="text-error more-info-err" style="color: red;">
                                    {{ $errors->first('status') }}
                                </p>
                            @endif
                        
                        </div>
                        <div class="form-group">
                            <label for="start_date" >Start Date</label>
                        
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}">
                            @if ($errors->has('start_date'))
                                <p class="text-error more-info-err" style="color: red;">
                                    {{ $errors->first('start_date') }}
                                </p>
                            @endif
                        
                        </div>
                        <div class="form-group">
                            <label for="start_date" >Start Time</label>
                        
                            <input type="time" class="form-control" id="start_time" name="start_time" value="{{ old('start_time') }}">
                            @if ($errors->has('start_time'))
                                <p class="text-error more-info-err" style="color: red;">
                                    {{ $errors->first('start_time') }}
                                </p>
                            @endif
                        
                        </div>
                        <div class="form-group">
                            <label for="end_date" >End Date</label>
                        
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}">
                            @if ($errors->has('end_date'))
                                <p class="text-error more-info-err" style="color: red;">
                                    {{ $errors->first('end_date') }}
                                </p>
                            @endif
                        
                        </div>
                        <div class="form-group">
                            <label for="end_time" >End Time</label>
                        
                            <input type="time" class="form-control" id="end_time" name="end_time" value="{{ old('end_time') }}">
                            @if ($errors->has('end_time'))
                                <p class="text-error more-info-err" style="color: red;">
                                    {{ $errors->first('end_time') }}
                                </p>
                            @endif
                        
                        </div>

                        <div class="form-group">
                            <label for="users">employees</label>
                       
                            <select class="form-control" id="users" name="users[]" multiple>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ in_array($employee->id, old('users', [])) ? 'selected' : '' }}>
                                       {{ $employee->first_name }} {{$employee->last_name}}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('users'))
                                <p class="text-error more-info-err" style="color: red;">
                                    {{ $errors->first('users') }}
                                </p>
                            @endif
                        
                        </div>
                        <div class="form-group py-2">
                            <div class="icheck-material-white">
                                <input type="checkbox"name="is_active" id="user-checkbox2" checked/>
                                <label for="user-checkbox2">Is Active</label>
                            </div>
                          </div>
                      
                      
                      <div class="form-group">
                       <button type="submit" class="btn btn-light px-5"><i class="icon-lock"></i> Save</button>
                     </div>
                     </form>
                    </div>
                    </div>
                 </div>
            </div>
            <div class="overlay toggle-menu"></div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#users').select2({
            placeholder: "Select users",
            
            allowClear: true
        });
    });
</script>

@endpush
