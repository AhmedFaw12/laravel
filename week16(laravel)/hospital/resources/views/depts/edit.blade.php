@extends("master")
@section('content')

<div class="d-flex justify-content-between mt-2">
    <h5>Edit {{$department->name}} Department</h5>
    <a class="btn btn-sm btn-primary m-1" href="{{route('department.index')}}" >List Departments</a>
</div>

<div class="container">
    <div class="row">
        <div class="col">
            {{-- <form method="POST" action="/department/store"> --}}
            <form method="POST" action="{{route('department.update', ["department"=>$department->id])}}">
                @csrf
                @method("PATCH")
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{$department->name}}" placeholder="Enter dept Name" aria-describedby="helpId">
                </div>

                <div class="form-group">
                    <label for="name">Main Department</label>
                    {{-- @dump($depts) --}}
                    <select class="form-control" name="department_id">
                        <option></option>
                        @foreach ($depts as $id=>$name)
                        <option
                            @if($department->department_id == $id)
                                selected
                            @endif
                        value="{{$id}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>
                <input type="submit" class="btn btn-sm btn-primary m-1" value="Save">
            </form>
        </div>
        <div class="col"></div>
    </div>
</div>

@endsection
