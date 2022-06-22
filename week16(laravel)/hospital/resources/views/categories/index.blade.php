<x-template-component>

<div class="d-flex justify-content-between">
    <h2>Departments</h2>
    {{-- <a class="btn btn-sm btn-primary m-1" href="/department/create" >Add Department</a> --}}
    <a class="btn btn-sm btn-primary m-1" href="{{route('department.create')}}" >Add Department</a>
</div>

@component('components.alert-component')
    Your message here
    @slot('style')
        primary
    @endslot
@endcomponent

<hr>

<x-alert-component>
    Welcome
    <x-slot name="style">
        success
    </x-slot>
</x-alert-component>

<div class="table-responsive">
  <table class="table table-striped table-sm">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">manager</th>
        <th scope="col">Main Department</th>
        <th scope="col">Created At</th>
        <th scope="col">Updated At</th>
        <th scope="col">&nbsp;</th>
      </tr>
    </thead>
    <tbody>
        @forelse($depts as $dept)
            <tr>
                {{-- @dump($loop) --}}
                {{-- <td>{{$dept->id}}</td> --}}

                <td>{{$loop->iteration}}</td>{{--starting from 1--}}
                <td>{{$dept->name}}</td>
                <td>{{$dept->manager_id}}</td>
                <td>
                    {{-- @php
                       $d =  App\Models\Department::find( $dept->department_id);
                       if($d){
                           echo $d->name;
                       }
                    @endphp --}}
                    {{($dept->department_id)? $dept->main_department->name: ""}}
                </td>
                <td>{{$dept->created_at}}</td>
                <td>{{$dept->updated_at}}</td>
                {{-- edit --}}
                <td>
                    <a href="{{route('department.edit', ["department"=>$dept->id])}}" class="btn btn-success btn-sm">edit</a>
                </td>
                {{-- delete --}}
                <td>
                    <form method="POST" action="{{route('department.destroy', ["department"=>$dept->id])}}">
                        @csrf
                        @method("delete")
                        <button type="submit" class="btn btn-danger btn-sm">delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">Empty Result</td>
            </tr>
        @endforelse

    </tbody>
  </table>
</div>

{{-- this displays 1,2,3, ... --}}
{{$depts->links()}}

{{--this displays next/previous  --}}
{{-- {{$depts->links("pagination::simple-bootstrap-4")}} --}}
</x-template-component>
