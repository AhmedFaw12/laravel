<?php
/*
Laravel Storage:
    -how to manage storage(upload file ,image , ....)
    -we can store inside project:
        -in public storage, local storage 
        -when we buy host or rent a machine , this machine(its hard disk) has a limit 
        -if we exceeded this limit , things will be slower
        -so we can get external storage 
    -we can store outside project:
        -in external storage
        -example shared cloud like : amazon s3 (50 GB for free at first), google
    ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    Storage Folder Contents:
        -Framework folder:
            -contents:
                -cache folder : caches the data,configs,settings to be accessed easily, faster
                -views Folder : contains (compiled views)blade files after converting them to native php.
                -sessions folder: stores any session data here
                -testing folder : any unit tests
            -Laravel manages this Framework Folder not me 

        -logs Folder:
            -laravel.log: has errors that appeared while running
            -we can delete laravel.log , and it will be re-created automatically whenever there are errors
        -app Folder:
            -where I will put my storage/files manually by me
            -contents:
                -public folder : 
                    -this is called public storage
            -outside public folder and inside app folder is called local storage
            -difference between public & local storage:
                -when we upload image ,then we want to display this image on website(UI)
                -we are used to display images from Public folder (main folder for assets,css,js, ..) not from storage/app/public.
                - laravel can make a link(shortcut) for storage/app/public in Public Folder
                -then we can access Public Folder using asset function

                -so any thing/resource/image used for UI ,we will store it in storage/app/public and make a shortcut for it in Public Folder to be easy accessable through asset function.

                -any Other things (not image for UI) will be stored in local storage
    ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    Filesystems.php file:
        -it is located in config folder
        -it is the main configuration file for the storage
        -Within this file, you may configure all of your filesystem "disks". Each disk represents a particular storage driver and storage location. 

        -it returns an array that contains:
            -default key : 
                -sets the default filesystem disk is local
                -we can change the default from .env from FILESYSTEM_DISK

            disks : 
                -where we can configure as many filesystem "disks" as you wish(public ,  local, s3 , ....)
                -storage_path() : 
                    -get the path of storage folder
                    -storage_path("app") :gets storage/app for local storage disk
                    -storage_path("app/public"): gets storage/app/public for public storage disk
    ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    
    How to Make storage link for storage/app/public in Public Folder:
        -Command : php artisan storage:link  
        
        -it will create symbolic link(similar to shortcut) for storage folder
        -this storage symbolic link will be link to storage/app/public 
        -if we created any file in storage/app/public ,it will appear in storage symbolic link and vice versa

        - while local storage can't be accessed from Public folder
    ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    

    How do we store files/images ?
        -usually forms uses POST method
        -use input type file
        -enc : multi type form data

        -files are stored in temp folder
        -so we will move them to safe location(storage/app/public) 
        -we will use storage class

    Example:
        navigation.blade.php:
            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-nav-link>
                <x-nav-link :href="route('users.list')" :active="request()->routeIs('users.list')">
                    {{ __('Users') }}
                </x-nav-link>
                <x-nav-link :href="route('department.index')" :active="request()->routeIs('department.index')">
                    {{ __('Departments') }}
                </x-nav-link>
            </div>

             <!-- Responsive Navigation Menu -->
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('users.list')" :active="request()->routeIs('users.list')">
                        {{ __('Users') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('department.index')" :active="request()->routeIs('department.index')">
                        {{ __('Departments') }}
                    </x-responsive-nav-link>
                </div>
                //
            </div>

            -we made links for departments, users , dashboard
            -we made same links in responsive devs for mobile or small pages

        
        web.php:
            Route::prefix("/")->middleware("auth")->group(function(){
                Route::get("/users", function(){
                    return view("users.list");
                })->name("users.list");

                //other routes
                Route::resource('department', DepartmentController::class);
            });

            -we made routes for department
            -we applied auth middleware where users only can access department, users routes

        departments migration table:
            public function up()
            {
                Schema::create('departments', function (Blueprint $table) {
                    $table->id();
                    $table->string("name");
                    $table->foreignId("manager_id")->nullable()->constrained("users");
                    $table->foreignId("department_id")->nullable()->constrained("departments");//for sub departments
                    $table->string("url")->nullable()->default("test/logo.png");//url for the file
                    $table->timestamps();
                });
            }
            -we added url (url of image/file) column
        index.blade.php :
            <td>
                <img style="width:50px" src="{{asset('storage/'.$dept->url)}}" alt="{{$dept->name}}">
            </td>
            -we displayed departments images
        
        create.blade.php:
            <form method="POST" action="{{route('department.store')}}" enctype="multipart/form-data">
                @csrf
                {{-- file input --}}
                <div class="form-group">
                    <label for="name">Logo</label>
                    <input type="file" name="url" id="url" class="form-control" aria-describedby="helpId">
                    @error("url")
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
            </form>
            -we made form to send file 
            -form with enctype="multipart/form-data"
            -we made input with type file
        DepartmentController.php:
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
            
            - "url"=> "nullable|image|max:2000"   : we validated url as image , with max size 2000 kilobytes
            -!empty($request->file("url")) :we checked that request has input file with name url(input name in the database)
            
            -$file_path = $request->file("url")->store("test") : we stored file in storage  folder in a created folder (test) 

            -$file_path = Storage::disk("public")->put("test", $request->file("url")) : another way to store file using storage class , disk :determing storage disk (public, local, s3) , put method that take distination folder and  input file

            -$dept->url = $file_path: saving file_path in database

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
            -we  delete old image ,then store the new image if exists
  
            public function destroy(Department $department)
            {
                // dd($department->url);
                Storage::delete($department->url);//delete image from storage folder
                $department->delete();//delete from database
                // return $department; //can be return as an object
                return redirect()->route("department.index");

            }

            -when we delete image from storage folder, we use storage class with delete method and give it the path

        edit.blade.php:
            <form method="POST" action="{{route('department.update', ["department"=>$department->id])}}" enctype= "multipart/form-data">
                @csrf
                @method("PATCH")

                {{-- file --}}
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="file" name="url" id="url" class="form-control" aria-describedby="helpId">
                    @error("url")
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
            </form>

            -we update new image
            
            <div class="col">
                <img style="width:25%" src="{{asset('storage/'.$department->url)}}" alt="{{$department->name}}">
            </div>

            -we displayed old image
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------



*/