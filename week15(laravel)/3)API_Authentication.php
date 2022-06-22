<?php
/*
API_Authentication:
    -We can apply Two Steps of Authentication:
        -Sending Api_key in the request (Api_key sent in the request must be the same as Api_key at the server) :
        
            Example :
                -add Api_key = any Value in the .env file
                -creating middleware for api to check for the Api_key sent in the request
                -we need to apply our middleware on all APIs , so put it in kernel in in middlewaregroup array api group

                -before receiving data , we will authenticate it
                -request consists of header(api_key , ....) and body (data)

                -Command to create middleware : php artisan make:middleware SecureApi

                .env:
                    # adding API_KEY
                    API_KEY = ABCD123
                
                SecureApi.php:
                    public function handle(Request $request, Closure $next)
                    {
                        if($request->header("API_KEY") == env("API_KEY")){

                            return $next($request);
                        }else{
                            return response()->json(["error"=>"Invalid API Key"],403);
                        }
                    }
                kernel.php:
                    use App\Http\Middleware\SecureApi;
                    protected $middlewareGroups = [
                        'web' => [
                            \App\Http\Middleware\EncryptCookies::class,
                            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                            \Illuminate\Session\Middleware\StartSession::class,
                            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                            \App\Http\Middleware\VerifyCsrfToken::class,
                            \Illuminate\Routing\Middleware\SubstituteBindings::class,
                        ],

                        'api' => [
                            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
                            'throttle:api',
                            \Illuminate\Routing\Middleware\SubstituteBindings::class,
                            SecureApi::class,
                        ],
                    ];
                
                -then test it on postman
        --------------------------------------------------------------------------------------------------------------------------------------------------------------------
        User/API Token:
            -Sometimes Api is free so we don't need api_key , but to call api ,we must register(having user)

            -since there is no session , server will send the user a token , so when the user needs something (he will not login again) , the user will just send the token and it will be validated with the one on the server

            How to make the user Token:
                -using external Libraries(packages): Sanctum library

        --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
         -LARAVEL SANCTUM:
            Introduction:
                Laravel Sanctum provides a featherweight authentication system for SPAs (single page applications), mobile applications, and simple, token based APIs. Sanctum allows each user of your application to generate multiple API tokens for their account. These tokens may be granted abilities / scopes which specify which actions the tokens are allowed to perform.                    
            -API Tokens:
                -First, Sanctum is a simple package you may use to issue(توزع) API tokens to your users. This feature is inspired by GitHub and other applications which issue "personal access tokens". For example, imagine the "account settings" of your application has a screen where a user may generate an API token for their account. You may use Sanctum to generate and manage those tokens. These tokens typically have a very long expiration time (years), but may be manually revoked by the user at anytime.

                -Laravel Sanctum offers this feature by storing user API tokens in a single database table(called : personal_access_tokens) and authenticating incoming HTTP requests via the Authorization header which should contain a valid API token.


            -Installation:
                -The most recent versions of Laravel already include Laravel Sanctum. However, if your application's composer.json file does not include laravel/sanctum, you may follow the installation instructions below 

                -You may install Laravel Sanctum via the Composer package manager:  
                composer require laravel/sanctum

                -Next, you should publish the Sanctum configuration and migration files using the vendor:publish Artisan command. The sanctum configuration file will be placed in your application's config directory and also create migration file for personal access token table:
                
                php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
                
                -Finally, you should run your database migrations. Sanctum will create one database table in which to store API tokens:
                    php artisan migrate
                
                -Next, if you plan to utilize Sanctum to authenticate an SPA, you should add Sanctum's middleware to your api middleware group within your application's app/Http/Kernel.php file:
                    'api' => [
                        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
                        'throttle:api',
                        \Illuminate\Routing\Middleware\SubstituteBindings::class,
                    ],

                
                -Issuing(using/توزيع) API Tokens:
                    Sanctum allows you to issue API tokens / personal access tokens that may be used to authenticate API requests to your application. When making requests using API tokens, the token should be included in the Authorization header as a Bearer token.

                    To begin issuing tokens for users, your User model should use the Laravel\Sanctum\HasApiTokens trait:

                    use Laravel\Sanctum\HasApiTokens;
                    
                    class User extends Authenticatable
                    {
                        use HasApiTokens, HasFactory, Notifiable;
                    }

                    HasApiTokens.php :
                        -HasApiTokens is a trait
                        
                        -To issue a token, you may use the createToken method. 
                        -The createToken method returns a Laravel\Sanctum\NewAccessToken instance. 
                        -API tokens are hashed using SHA-256 hashing before being stored in your database, but you may access the plain-text value of the token using the plainTextToken property of the NewAccessToken instance. 
                        
                        -You should display this value to the user immediately after the token has been created:

                        
                        public function createToken(string $name, array $abilities = ['*'])
                        {
                            $token = $this->tokens()->create([
                                'name' => $name,
                                'token' => hash('sha256', $plainTextToken = Str::random(40)),
                                'abilities' => $abilities,
                            ]);

                            return new NewAccessToken($token, $token->getKey().'|'.$plainTextToken);
                        }

                        -   *  means all abilities are granted
                        - SHA-256 hashing is a hashing technique
            -------------------------------------------------------------------------------------------------------------------------------------------------------------------------
            -Protecting Routes:
                Example:
                    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
                        return $request->user();
                    });
                -To protect routes so that all incoming requests must be authenticated, you should attach the sanctum authentication guard to your API routes within your routes/api.php file. 
                This guard(middleware) will ensure that incoming requests are authenticated as either a stateful authenticated requests from your SPA or contain a valid API token header if the request is from a third party:

                -To use this route there must be a route login 
                -because the upper route will get the user who logged in
                    Route::post("login", [UserController::class, "login"])->name("login");

                    -name : to add route name 
                
                
            -Example:
                api.php:
                    -To protect routes so that all incoming requests must be authenticated, you should attach the sanctum authentication guard to your API routes
                    -To use this route there must be a route login 
                    -because the lower route will get the user who logged in
                
                    //defining route login
                    Route::post("login", [UserController::class, "login"])->name("login");

                    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
                        return $request->user();
                    });


                
                    -we made a route group so that the user must login and the api token is authenticated to use these routes

                    //Add guard to multiple routes
                    Route::prefix("/")->middleware("auth:sanctum")->group(function(){
                        //making API routes For EvManufacturer controller using apiResource() Method
                        Route::apiResource("manufacturer", EvManufacturerController::class);
                        Route::apiResource("ev", EvController::class);
                        Route::get("/my_evs", [EvController::class, "userEvs"]);
                    });



                UserController.php:

                    -login function for login route 
                    -we must validate request to see if there is email or password and if email is in email format
                    -compare email , password with those in database using Auth::attempt() method
                    -Auth::user() will return user model instance
                    -createToken() : we will give it the name of token we want  to create(as parameter), we will name it after username
                    -createToken () will return NewAccessToken class instance
                    -we can get the token(unhashed) using  plainTextToken property in NewAccessToken

                    -then return json contain user data , and token created

                    function login(Request $request){
                        $validator = Validator::make($request->all(), [
                            "email" =>"required|email", //validate that it is required and it is in email format
                            "password"=>"required"
                        ]);

                        if($validator->fails()){
                            return response()->json($validator->errors(), 405);
                        }

                        $credentials = $request->only("email", "password");

                        if(Auth::attempt($credentials)){
                            // return Auth::user();
                            //create token and return it as a json
                            return [
                                "user"=>Auth::user(),
                                "token" =>Auth::user()->createToken(Auth::user()->name)->plainTextToken
                            ];
                        }else{
                            return response()->json("Invalid Email or Password", 404);
                        }
                    }

                EvController.php:
                    
                    public function index()
                    {
                        return Ev::all();
                    }

                    //return all evs for a given user 
                    public function userEvs(){
                        return Auth::user()->evs;
                    }
                
                -Postman Test :
                    1)make a request for login: 
                        -Post http://localhost:8000/api/login

                        -pass API_KEY ABCD123 in the header
                        -pass to the body : email , password
                    2)make request for user Api token:
                        -GET http://localhost:8000/api/user
                        -pass to the header:
                            Authorization    Bearer user_token
                            API_KEY          ABCD123
                    3)make request for my_evs:
                        -GET http://localhost:8000/api/my_evs
                        -pass to the header:
                            Authorization    Bearer user_token
                            API_KEY          ABCD123

-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

Different Methods:
    Request Methods :
        -$request->all():
            -You may retrieve all of the incoming request's input data as an array using the all method.  

        -$request->only():
            -If you need to retrieve a subset of the input data, you may use the only method
            -accept a single array or a dynamic list of arguments

            -The only method returns all of the key / value pairs that you request; however, it will not return key / value pairs that are not present on the request.

            example:
                $credentials = $request->only("email", "password");

        -$request->except():
            -The only method returns all of the key / value pairs that are not present on the request
            -example :
                $input = $request->except('credit_card');
        
        -Determining If Input Is Present:
            -has() :
                -you may use the has method to determine if a value is present on the request. The has method returns true if the value is present on the request

                -example 1 :
                    if ($request->has('name')) {
                        //
                    }
                -example2:
                    When given an array, the has method will determine if all of the specified values are present:
                        if ($request->has(['name', 'email'])) {
                            //
                        }
            -whenHas():
                -The whenHas method will execute the given closure/function if a value is present on the request:
                -example :
                    -$request->whenHas('name', function ($input) {
                            //
                    });
                
                -example 2 :
                    -A second closure may be passed to the whenHas method that will be executed if the specified value is not present on the request:

                    $request->whenHas('name', function ($input) {
                        // The "name" value is present...
                    }, function () {
                        // The "name" value is not present...
                    });
            -hasAny():
                -The hasAny method returns true if any of the specified values are present:

                if ($request->hasAny(['name', 'email'])) {
                    //
                }
            -filled():
                If you would like to determine if a value is present on the request and is not empty, you may use the filled method:

                if ($request->filled('name')) {
                    //
                }
            -whenFilled():
                -The whenFilled method will execute the given closure if a value is present on the request and is not empty:

                $request->whenFilled('name', function ($input) {
                    //
                });

                -A second closure may be passed to the whenFilled method that will be executed if the specified value is not "filled":

                $request->whenFilled('name', function ($input) {
                    // The "name" value is filled...
                }, function () {
                    // The "name" value is not filled...
                });
    --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    Authentication Class Methods:
        -Auth::attempt($credentials) : 
            -The attempt method accepts an array of key / value pairs as its first argument. 
            -The values in the array will be used to find the user in your database table. 
            -So, in the example above, the user will be retrieved by the value of the email column. 
            -If the user is found, the hashed password stored in the database will be compared with the password value passed to the method via the array. 
            -You should not hash the password specified as the password value, since the framework will automatically hash the value before comparing it to the hashed password in the database. 
            -If the two hashed passwords match an authenticated session will be started for the user.

            -The attempt method will return true if authentication was successful. Otherwise, false will be returned.
        -Auth::ModelClassName() :
            - will get object of this model class 
            -when we validate sanctum 
            -object of this model class is created and put in Auth direct

            -Auth can be used as function or class
            -it will return current object from token passed if we validate token
            -it will return current object from session if we validate session
            example :
                Auth()->user()->evs;
                Auth::user()->evs;
    
    Route Group Methods:
        -prefix():
            The prefix method may be used to prefix each route in the group with a given URI.
        -group():
            -Route groups allow you to share route attributes, such as middleware, across a large number of routes without needing to define those attributes on each individual route.
        -middleware():
            -To assign middleware to all routes within a group, you may use the middleware method before defining the group. 

        example : 
            Route::prefix("/")->middleware("auth:sanctum")->group(function(){
                //making API routes For EvManufacturer controller using apiResource() Method
                Route::apiResource("manufacturer", EvManufacturerController::class);
                Route::apiResource("ev", EvController::class);
                Route::get("/my_evs", [EvController::class, "userEvs"]);
            });
*/