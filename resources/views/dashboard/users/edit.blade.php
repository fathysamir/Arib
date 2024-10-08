@extends('dashboard.layout.app')
@section('title', 'Dashboard - edit user')
@section('content')	
    <div class="content-wrapper">
        <div class="container-fluid">

            <div class="row mt-3">
                <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body">
                      <div class="card-title">Edit Client</div>
                      <hr>
                       <form method="post" action="{{ route('update.user', ['id' => $user->id]) }}" enctype="multipart/form-data">
                        @csrf
                      <div class="form-group">
                       <label>First Name</label>
                        <input type="text" name="first_name" class="form-control"  placeholder="Enter First Name"value="{{ old('first_name',$user->first_name) }}">
                        @if ($errors->has('first_name'))
                            <p class="text-error more-info-err" style="color: red;">
                                {{ $errors->first('first_name') }}</p>
                        @endif
                      </div>
                      <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control"  placeholder="Enter Last Name"value="{{ old('last_name',$user->last_name) }}">
                        @if ($errors->has('last_name'))
                            <p class="text-error more-info-err" style="color: red;">
                                {{ $errors->first('last_name') }}</p>
                        @endif
                      </div>
                      <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control"  placeholder="Enter Email"value="{{ old('email',$user->email) }}">
                        @if ($errors->has('email'))
                            <p class="text-error more-info-err" style="color: red;">
                                {{ $errors->first('email') }}</p>
                        @endif
                      </div>
                      <div class="form-group">
                        <label>Phone Number</label>
                        <input type="number" name="phone_number" class="form-control"  placeholder="Enter Phone Number"value="{{ old('phone_number',$user->phone) }}">
                        @if ($errors->has('phone_number'))
                            <p class="text-error more-info-err" style="color: red;">
                                {{ $errors->first('phone_number') }}</p>
                        @endif
                      </div>
                      <div class="form-group">
                        <label>Salary</label>
                        <input type="number" name="salary" class="form-control"  placeholder="Enter Salary"value="{{ old('salary',$user->salary) }}">
                        @if ($errors->has('salary'))
                            <p class="text-error more-info-err" style="color: red;">
                                {{ $errors->first('salary') }}</p>
                        @endif
                      </div>
                      <div class="form-group">
                        <label>Department</label>
                        
                        <select class="form-control" name="department">
                          <option value="">Select Department</option>
                          @foreach($departments as $department)
                          <option value="{{$department->id}}" @if($user->department_id==$department->id) selected @endif>{{$department->name}}</option>
                          @endforeach
                        </select>
                        @if ($errors->has('department'))
                            <p class="text-error more-info-err" style="color: red;">
                                {{ $errors->first('department') }}</p>
                        @endif
                      </div>
                      <div class="form-group">
                        <label>Role</label>
                        
                        <select class="form-control" name="role">
                          <option value="">Select Role</option>
                          @foreach($roles as $role)
                          <option value="{{$role->id}}" @if($user->roles->first()->id==$role->id) selected @endif>{{$role->name}}</option>
                          @endforeach
                        </select>
                        @if ($errors->has('role'))
                            <p class="text-error more-info-err" style="color: red;">
                                {{ $errors->first('role') }}</p>
                        @endif
                      </div>
                      <div class="form-group" id="manager_div" @if($user->roles->first()->name!='Employee')style="display: none;"@endif>
                        <label>Manager</label>
                        
                        <select class="form-control" name="manager">
                          <option value="">Select Manager</option>
                          @foreach($managers as $manager)
                          <option value="{{$manager->id}}" @if($user->manager_id==$manager->id) selected @endif>{{$manager->first_name}} {{$manager->last_name}}</option>
                          @endforeach
                        </select>
                        @if ($errors->has('manager'))
                            <p class="text-error more-info-err" style="color: red;">
                                {{ $errors->first('manager') }}</p>
                        @endif
                      </div>
                      <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control" id="imageInput" placeholder="Choose Image" onchange="showImage(event)">
                        <img id="imagePreview" class="img-thumbnail" src="{{$user->image}}" alt="Selected Image" style="@if($user->image==null) display: none; @endif height:100px;">
                        @if ($errors->has('image'))
                            <p class="text-error more-info-err" style="color: red;">
                                {{ $errors->first('image') }}</p>
                        @endif
                      </div>
                      
                      
                      <div class="form-group">
                       <button type="submit" class="btn btn-light px-5"><i class="icon-lock"></i> Register</button>
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
  function showImage(event) {
      var imageInput = event.target;
      var imagePreview = document.getElementById('imagePreview');
      
  
      if (imageInput.files && imageInput.files[0]) {
          var reader = new FileReader();
  
          reader.onload = function(e) {
              imagePreview.src = e.target.result;
              imagePreview.style.display = 'block';
          }
  
          reader.readAsDataURL(imageInput.files[0]);
      }
  }
  </script>
<script>
  $(document).ready(function() {
    // Function to handle the visibility of manager_div
    function toggleManagerDiv() {
      var selectedRoleText = $('select[name="role"] option:selected').text();
      var selectedDepartment = $('select[name="department"]').val(); // Get selected option text
      if (selectedRoleText === "Employee" && selectedDepartment !== "") {
        $('#manager_div').show();
        fetchManagers(selectedDepartment); // Show the Manager div
      } else {
        $('#manager_div').hide(); // Hide the Manager div
        $('#manager_div select').empty().append('<option value="">Select Manager</option>'); // Clear the Manager dropdown value
      }
    }
    function fetchManagers(selectedDepartment) {
        $.ajax({
            url: '/fetch-managers',
            type: 'GET',
            data: {
                department: selectedDepartment
            },
            success: function(data) {
                var managers = data.managers;
                var managerSelect = $('#manager_div select');

                managerSelect.empty().append('<option value="">Select Manager</option>');

                managers.forEach(function(manager) {
                    managerSelect.append('<option value="' + manager.id + '">' + manager.first_name + ' ' + manager.last_name + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
    $('select[name="department"]').on('change', function() {
        var selectedRoleText = $('select[name="role"] option:selected').text();
        var selectedDepartment = $(this).val();

        if (selectedRoleText === "Employee" && selectedDepartment !== "") {
          fetchManagers(selectedDepartment);
        }
    });
    // On page load
    toggleManagerDiv();

    // On change event
    $('select[name="role"]').on('change', function() {
      toggleManagerDiv(); // Call the function whenever the role changes
    });
  });
</script>
@endpush
