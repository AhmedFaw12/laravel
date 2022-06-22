<?php

namespace App\Http\Controllers;

use App\Events\DeleteDeptEvent;
use App\Rules\AhmedRule;
use App\Models\Department;
use App\Mail\DeletedDepartment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return view("depts.index", ["depts" => Department::all()]);
        return view("depts.index", ["depts" => Department::paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("depts.create", ["depts"=>Department::pluck("name", "id")]);//will return associative array/collection ,where id value will be the key(key is the 2nd parameter) while name value will be the value
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDepartmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDepartmentRequest $request)
    {
        //validate request coming from form
        $request->validate([
            // "name"=>['required', new AhmedRule()],
            "name"=>['required'],
            "department_id"=>"nullable|integer",
            "url"=> "nullable|image|max:2000",
        ]);
        $file_path = null;
        if(!empty($request->file("url"))){
            // $file_path = $request->file("url")->store("test"); //store file in test folder in public storage

            $file_path = Storage::disk("public")->put("test", $request->file("url"));
        }
        //create model object
        // dump($request->all());
        $dept = new Department();
        $dept->name =$request->name;
        $dept->department_id = $request->department_id;
        $dept->url = $file_path;
        $dept->save();//to insert record into table

        return redirect()->to("/department");//url
        // return redirect()->route("department.index");//route name
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        return $department;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        // ["department"=>$department] same as compact("department")
        return view("depts.edit", ["depts"=>Department::pluck("name", "id")])->with(compact("department"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDepartmentRequest  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $file_path = null;
        if(!empty($request->file("url"))){
            Storage::delete($department->url);
            // $file_path = $request->file("url")->store("test"); //store file in test folder in public storage

            $file_path = Storage::disk("public")->put("test", $request->file("url"));
        }

        $department->name = $request->name;
        $department->department_id = $request->department_id;
        if(!empty($file_path)) $department->url = $file_path;
        $department->save();//save do both insert if new record , if exists record update record
        return redirect()->route("department.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        // dd($department->url);
        Storage::delete($department->url);//delete image from storage folder
        $department->delete();//delete from database


        // Mail::to("admin@aih.com")->send(new DeletedDepartment($department));

        //firing event
        DeleteDeptEvent::dispatch($department);

        // return $department; //can be return as an object
        return redirect()->route("department.index");

    }
}
