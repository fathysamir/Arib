@extends('dashboard.layout.app')
@section('title', 'Dashboard - edit project')
@section('content')	
    <div class="content-wrapper">
        <div class="container-fluid">

            <div class="row mt-3">
                <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body">
                      <div class="card-title">Update Project</div>
                      <hr>
                       <form method="post" action="{{route('update.project',['id' => $project->id])}}" enctype="multipart/form-data">
                        @csrf
                      <div class="form-group">
                       <label>Name</label>
                        <input type="text" name="name" class="form-control"  placeholder="Enter Name"value="{{ old('name',$project->name) }}">
                        @if ($errors->has('name'))
                            <p class="text-error more-info-err" style="color: red;">
                                {{ $errors->first('name') }}</p>
                        @endif
                      </div>
                      <div class="form-group py-2">
                        <div class="icheck-material-white">
                            <input type="checkbox"name="is_active" id="user-checkbox2" @if($project->is_active==1) checked @endif/>
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
<script>
   
</script>
@endpush
