@extends("master")
@section('content')

<div class="d-flex justify-content-between mt-2">
    <h5>Add New Department</h5>
    <a class="btn btn-sm btn-primary m-1" href="{{route('department.index')}}" >List Departments</a>
</div>

<div class="container">
    <div class="row">
        <div class="col">
            {{-- displaying all errors --}}
            {{-- @foreach($errors->all() as $error)
                {{$error}}
            @endforeach --}}
            {{-- <form method="POST" action="/department/store"> --}}
            <form method="POST" action="{{route('department.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{old('name')}}" placeholder="Enter dept Name" aria-describedby="helpId">
                    @error("name")
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                {{-- file input --}}
                <div class="form-group">
                    <label for="name">Logo</label>
                    <input type="file" name="url" id="url" class="form-control" aria-describedby="helpId">
                    @error("url")
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">Main Department</label>
                    {{-- @dump($depts) --}}
                    <select class="form-control" name="department_id">
                        <option></option>
                        @foreach ($depts as $id=>$name)
                        <option value="{{$id}}">{{$name}}</option>
                        @endforeach
                    </select>
                    @error("department_id")
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                {{-- age --}}
                {{-- <div class="form-group">
                    <label for="age">Age</label>
                    <input type="text" name="age" id="age" class="form-control" value="{{old('age')}}" placeholder="Enter dept Name" aria-describedby="helpId">
                    @error("age")
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div> --}}

                <input type="submit" class="btn btn-sm btn-primary m-1" value="Save">
            </form>
        </div>
        <div class="col"></div>
    </div>
</div>

@endsection
