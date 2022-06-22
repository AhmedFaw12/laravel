<?php
/*

API(Application Programming Interface):
    -How To make API ? 
        api.php:
            Route::apiResource("manufacturer", EvManufacturerController::class);

            -making API routes For EvManufacturer controller using apiResource() Method
            -this will create 5 routes only(index, store, show, update, destroy) , because we dont have form:
                
                GET|HEAD        (url)api/manufacturer .. (controller method)manufacturer.index › EvManufacturerController@index 

                POST            api/manufacturer .. manufacturer.store › EvManufacturerController@store 
                
                GET|HEAD        api/manufacturer/{manufacturer} ... manufacturer.show › EvManufacturerController@show 
                
                PUT|PATCH       api/manufacturer/{manufacturer} .. manufacturer.update › EvManufacturerController@update 
                
                DELETE          api/manufacturer/{manufacturer} .. manufacturer.destroy › EvManufacturerController@destroy 
        
        -EvManufacturerController.php:
            -index():
                -Use GET when request
                -create an api to return all EvManufacturers as a collection
            -Store():
                -Use POST when request
                -Bad example using validate():
                    use Illuminate\Http\Request;
                    public function store(Request $request)
                    {
                        //validate request
                        $rslt = $request->validate([
                            'name' => "required",
                            'updated_by' => "required|exists:users,id",
                        ]);

                        // $m = new EvManufacturer();
                        // $m->name = $request->name;
                        // $m->updated_by = $request->updated_by;
                        // $m->save();

                        // return $m;

                    }
                    
                    -when validate finish , it will automatically return  to the view that  i came from, but we want that when there is error to be returned as a data 
                    
                -Good Example using Validator class :
                    use Illuminate\Support\Facades\Validator;
                    public function store(Request $request)
                    {
                        //validate request
                        $validator = Validator::make($request->all(),[
                            'name' => "required",
                            'updated_by' => "required|exists:users,id",
                        ]);

                        if($validator->fails()){
                            return response()->json($validator->errors(), 405);
                        }

                        // dump($request->all());//return array

                        $m = new EvManufacturer();
                        $m->name = $request->name;
                        $m->updated_by = $request->updated_by;
                        $m->save();

                        return $m;

                    }
                    -using validator class , we can return error as data in response
                    -we created json response that contains errors as data , and changed the status of response (405) method not Allowed
                    -if we return errors directly , errors are returned , but status will be 200 which means success (there is not error on server and called api correctly):
                        return $validator->errors()
    ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    -How to Test Api ?
        -using application called : postman
        -it can be downloaded as standalone app or as extension in google
        -we can make request to the api using postman
        steps:
            -select request
            -select request method(get, post, put, delete)
            -write url
            -body to add parameter to be sent(to the request)
            -then send 
    ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    API Resources :
        -When building an API, you may need a transformation layer that sits between your Eloquent models and the JSON responses that are actually returned to your application's users. 
        -For example, you may wish to display certain attributes for a subset of users and not others, or you may wish to always include certain relationships in the JSON representation of your models.
        
        -Eloquent's resource classes allow you to expressively and easily transform your models and model collections into JSON.
        
        -Generating Resources:
            -To generate a resource class, you may use the make:resource Artisan command.
            -By default, resources will be placed in the app/Http/Resources directory of your application. 
            -Resources extend the Illuminate\Http\Resources\Json\JsonResource class:
            -Resources transform individual models to JSON
        

            -the command is : php artisan make:resource UserResource

        -Writing Resources:
            -In essence, resources are simple. They only need to transform a given model into an array. 
            -So, each resource contains a toArray method which translates your model's attributes into an API friendly array that can be returned from your application's routes or controllers:

            Example:
                EvManufacturerResource.php:
                    public function toArray($request)
                    {
                        return [
                            "m_id"=> $this->id,
                            "m_name"=> $this->name,
                            "ev_models_count"=>$this->ev_models->count(), //return a relation
                        ];
                    }
                EvManufacturerController.php:
                    use App\Http\Resources\EvManufacturerResource;
                    
                    public function show(EvManufacturer $manufacturer)
                    {
                        return new EvManufacturerResource($manufacturer);
                    }
                    -Once a resource has been defined, it may be returned directly from a route or controller
                    
                    -parameters in  functions of any Controller must be as the name of the route created

                    -output : 
                        {
                            "data": {
                                "m_id": 1,
                                "m_name": "consequatur",
                                "ev_models_count": 5
                            }
                        }
        -Data Wrapping:
            By default, your outermost resource is wrapped in a data key when the resource response is converted to JSON. So, for example, a typical resource collection response looks like the following:
                {
                    "data": [
                        {
                        "m_id": 1,
                        "m_name": "consequatur",
                        "ev_models_count": 5,
                        },
                        {
                        "m_id": 2,
                        "m_name": "consequddatur",
                        "ev_models_count": 2,
                        }
                    ]
                }
            Disable Wrapping :
                -If you would like to disable the wrapping of the outermost resource, you should invoke the withoutWrapping method on the base Illuminate\Http\Resources\Json\JsonResource class. 
                -Typically, you should call this method from your AppServiceProvider or another service provider that is loaded on every request to your application:
                
                AppServiceProvider:
                    public function boot()
                    {
                        JsonResource::withoutWrapping();
                    }
                
            -If you would like to use a custom key instead of data, you may define a $wrap attribute on the resource class Even if we used withoutWrapping:
                Example:
                    class UserResource extends JsonResource
                    {
                        //The "data" wrapper that should be applied. 
                        public static $wrap = 'user';
                    }
        --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

        -Resource Collections:
            If you are returning a collection of resources, you should use the collection method provided by your resource class when creating the resource instance in your route or controller:

            EvManufacturerController.php:
                public function index()
                {
                    return EvManufacturerResource::collection(EvManufacturer::get());//using resource collection method
                }

            -Note that this does not allow any addition of custom meta data that may need to be returned with your collection. If you would like to customize the resource collection response, you may create a dedicated resource to represent the collection:


            Generating Resource Collection Class:
                -Once the resource collection class has been generated, you may easily define any meta data that should be included with the response:
                
                -php artisan make:resource UserResourceCollection --collection

                -Resource Collection extends Illuminate\Http\Resources\Json\ResourceCollection

            Example :
                EvManufacturerResourceCollection:
                    public function toArray($request)
                    {
                        return[
                            "data" =>$this->collection,
                            "info" =>[
                                "version"=>"v1",
                                "developed_by"=>"Ahmed Fawzy",
                            ],
                        ];
                    }
                
                EvManufacturerController.php:
                    index:
                        -After defining your resource collection, it may be returned from a route or controller:
                        public function index()
                        {
                            // return EvManufacturer::get();//return all data as collection
                            // return EvManufacturerResource::collection(EvManufacturer::get());//using resource collection method

                            //using ResourceCollection Class
                            return new EvManufacturerResourceCollection(EvManufacturer::get());
                        }

                Output:
                {
                    "data": [
                        {
                            "m_id": 1,
                            "m_name": "consequatur",
                            "ev_models_count": 5
                        },
                        {
                            "m_id": 2,
                            "m_name": "distinctio",
                            "ev_models_count": 8
                        },
                    ],
                    "info": {
                        "version": "v1",
                        "developed_by": "Ahmed Fawzy"
                    }
                }
            ------------------------------------------------------------------------------------------------------------------------------------------------------------
            Example :
                -create User Controller, UserResource, UserResourceCollection
        
                -php artisan make:controller UserController --resource --model=User
                --resource :create all crud operations/methods 
                --model=User :makes parameters in functions of model type (user for example)

                php artisan make:resource UserResource 
                php artisan make:resource UserResourceCollection --collection
                
                UserResource:
                    public function toArray($request)
                    {
                        return[
                            "id"=>$this->id,
                            "name"=>$this->name,
                            "evs"=>$this->evs,
                        ];
                    }
                UserResourceCollection:
                    public function toArray($request)
                    {
                        return[
                            "data" =>$this->collection,
                            "info" =>[
                                "version"=>"v1",
                                "developed_by"=>"Ahmed Fawzy",
                            ]
                        ];
                    }

                api.php:
                    Route::apiResource("user", UserController::class);


                UserController:
                    public function index()
                    {
                        return new UserResourceCollection(User::get());
                    }

                    public function show(User $user)
                    {
                        return new UserResource($user);
                    }
            ------------------------------------------------------------------------------------------------------------------------------------------------------------
            
--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Different Methods:
    1)Route::apiResource("manufacturer", EvManufacturerController::class);

            -making API routes For EvManufacturer controller using apiResource() Method
            -this will create 5 routes only(index, store, show, update, destroy) , because we dont have form.
    2)Log::info(json_encode($manufacturer)):
        -To help you learn more about what's happening within your application, Laravel provides robust logging services that allow you to log messages to files

        -data is written in laravel.log (storage/logs/laravel.log)


--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Validator Class:
    -Class to validate requests
    -If you do not want to use the validate method on the request, you may create a validator instance manually using the Validator facade. The make method on the facade generates a new validator instance:

    -use Illuminate\Support\Facades\Validator;

    methods:
        1)make(array, rules):
            -The make method on the facade generates a new validator instance:
                Example :
                    $validator = Validator::make($request->all(), [
                        'title' => 'required|unique:posts|max:255',
                        'body' => 'required',
                    ]);
                    -$request->all() :return array of all of the inputs on the request
        2)fails():
            -what to do on failure
            example:
                if($validator->fails()){
                    return response()->json($validator->errors()->add("myerror", "Not Correct"), 405);
                }
        3)errors():
            -Get all of the validation error messages.
            -$validator->errors();
        4)add("key", "errorMessage"):
            -add error message to the message bag
            -example :
                -$validator->errors()->add("myerror", "Not Correct")
                o/p:
                .....
                .....,
                "myerror": [
                    "Not Correct"
                ]
        5)Response():
            return a new response from the application.
        6)json(data, status):
            Create a new JSON response instance.
            example:
                -return response()->json($validator->errors(), 405);
            
            status :
                -200 : success :
                    Indicates that the request has succeeded.
                -401 Unauthorized:
                    -Although the HTTP standard specifies "unauthorized", semantically this response means "unauthenticated". That is, the client must authenticate itself to get the requested response

                -403 : Forbidden :
                    -Unauthorized request. The client does not have access rights to the content. Unlike 401, the client’s identity is known to the server.
                -404 : Not Found :
                    -The server can not find the requested resource.
                -405 : Method not allowed
                    -The request HTTP method is known by the server but has been disabled and cannot be used for that resource.

*/