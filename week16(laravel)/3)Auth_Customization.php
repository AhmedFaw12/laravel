<?php

/*
Breeze_Auth_Customization:
    -we want  to change in Breeze (form, add mobile, .....)

    Example 1 (Adding Mobile to Registeration):
        Users_Migration_table:
            public function up()
            {
                Schema::create('users', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('email')->unique();
                    $table->string('mobile')->unique();//added mobile column
                    $table->timestamp('email_verified_at')->nullable();
                    $table->string('password');
                    $table->rememberToken();
                    $table->timestamps();
                });
            }
            -added mobile column
        
        components/application-logo.blade.php:
            {{-- adding our logo --}}
            <img class="w-60 h-40" src="{{asset('images/logo.png')}}">
           
            -we changed logo

        views/auth/register.blade.php:
            <!-- Adding Mobile -->
            <div class="mt-4">
                <x-label for="mobile" :value="__('Mobile')" />

                <x-input id="mobile" class="block mt-1 w-full" type="text" name="mobile" :value="old('mobile')" required />
            </div>    

            -add mobile ui

        Controllers/Auth/RegisteredUserController:
            public function store(Request $request)
            {
                $request->validate([
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'mobile' => ['required', 'string', 'max:11', 'unique:users'],
                    'password' => ['required', 'confirmed', Rules\Password::defaults()],
                ]);

                //we used create method to use fillable property
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'password' => Hash::make($request->password),
                ]);

                event(new Registered($user));

                Auth::login($user);

                return redirect(RouteServiceProvider::HOME);
            }

            -we validated mobile input and added mobile to the database

            -RouteServiceProvider::HOME :
                -RouteServiceProvider : is a class in providers folder
                -Home is a public property in this class that contain default route for home after successful registeration

                -public const HOME = '/dashboard';

        Models/User.php:
            protected $fillable = [
                'name',
                'email',
                'mobile', //added mobile to be filled in mobile column
                'password',
            ];

            -added mobile to be filled in mobile column using create() method
    ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    
    Example2(if we want to login using mobile or email):
        views/auth/login.blade.php:
            <!-- Email Address or Mobile -->
            <div>
                <x-label for="emailOrMobile" :value="__('Email or Mobile')" />

                <x-input id="emailOrMobile" class="block mt-1 w-full" type="text" name="emailOrMobile" :value="old('emailOrMobile')" required autofocus />
            </div>

            -old() : to get old input value

        Controllers/Auth/AuthenticatedSessionController.php:
            public function store(LoginRequest $request)
            {
                $request->authenticate();

                $request->session()->regenerate();

                return redirect()->intended(RouteServiceProvider::HOME);
            }

            -authenticate() is a method in LoginRequest class :that is used to validate and authenticate the input

        Http/$requests/LoginRequest.php:
            public function rules()
            {
                return [
                    // 'email' => ['required', 'string', 'email'],
                    'emailOrMobile' => ['required', 'string'],
                    'password' => ['required', 'string'],
                ];
            }

            --rules :are the validation rules  for input/request
            
            protected function credentials(){
                if(is_numeric($this->get("emailOrMobile"))){
                    return ["mobile" =>$this->get("emailOrMobile") , "password" =>$this->get("password")];
                }elseif(filter_var($this->get("emailOrMobile"), FILTER_VALIDATE_EMAIL)){
                    return ["email" =>$this->get("emailOrMobile") , "password" =>$this->get("password")];
                }
                return ["username" =>$this->get("emailOrMobile") , "password" =>$this->get("password")];
            }

            -we overriden credentials method to check whether input(emailOrMobile) is email or mobile
            -we used $this-> : because we are already in the LoginRequest class

            public function authenticate()
            {
                $this->ensureIsNotRateLimited();

                // if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
                if (! Auth::attempt($this->credentials(), $this->boolean('remember'))) {
                    RateLimiter::hit($this->throttleKey());

                    throw ValidationException::withMessages([
                        'email' => trans('auth.failed'),
                    ]);
                }

                RateLimiter::clear($this->throttleKey());
            }

            -Auth::attempt  : to search in db with given credentials
    --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    Example3(Close Registeration):
        routes/auth.php:
            
            Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

            Route::post('register', [RegisteredUserController::class, 'store']);
            
            --we can comment register routes
        welcome.blade.php:
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a>
            @endif
        
            -we can also comment register link but it is not neccessary
            -because it will only display register link if there is register route
            -and we already commented register route
        
        -we close register in a company and employees 
        -they all have accounts and they don't register
    
    --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    Http/Middleware/RedirectIfAuthenticated.php:
        public function handle(Request $request, Closure $next, ...$guards)
        {
            $guards = empty($guards) ? [null] : $guards;

            foreach ($guards as $guard) {
                if (Auth::guard($guard)->check()) {
                    return redirect(RouteServiceProvider::HOME);
                }
            }

            return $next($request);
        }

        -RedirectIfAuthenticated is a class to check if user already authenticated using check() method

        -if user already logged in , and he want to log in again using route login , 
        RedirectIfAuthenticated will redirect me to the home page

        -we give this middleware a name (guest) in the kernel.php
    --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    
    Authentication:
        -we want to make login page for users and login page for admins
        -we want to authenticate users and admins
        
        -Many web applications provide a way for their users to authenticate with the application and "login". Implementing this feature in web applications can be a complex and potentially risky endeavor. For this reason, Laravel strives to give you the tools you need to implement authentication quickly, securely, and easily.

        -At its core, Laravel's authentication facilities are made up of "guards" and "providers":
        
            -Guards define how users are authenticated for each request. For example, Laravel ships with a session guard which maintains state using session storage and cookies.

            -Providers define how users are retrieved from your persistent storage (database). Laravel ships with support for retrieving users using Eloquent and the database query builder. However, you are free to define additional providers as needed for your application.

        -Your application's authentication configuration file is located at config/auth.php. This file contains several well-documented options for tweaking(modifying) the behavior of Laravel's authentication services.

        -Ecosystem Overview(نظرة عامة على النظام):
        -How authentication works When using a web browser:
            -When using a web browser a user will provide their username and password via a login form. If these credentials are correct, the application will store information about the authenticated user in the user's session. A cookie issued to the browser contains the session ID so that subsequent requests to the application can associate the user with the correct session. After the session cookie is received, the application will retrieve the session data based on the session ID, note that the authentication information has been stored in the session, and will consider the user as "authenticated". 
            
        -How authentication works When using APIs:
            -When a remote service needs to authenticate to access an API, cookies,sessions are not typically used for authentication because there is no web browser. Instead, the remote service sends an API token to the API on each request. The application may validate the incoming token against a table of valid API tokens and "authenticate" the request as being performed by the user associated with that API token.

        Laravel's Built-in Browser(web) Authentication Services:
            -Laravel includes built-in authentication and session services which are typically accessed via the Auth and Session facades(واجهات). 
            
            -These features provide cookie-based authentication for requests that are initiated from web browsers. They provide methods that allow you to verify a user's credentials and authenticate the user. 
            
            -In addition, these services will automatically store the proper authentication data in the user's session and issue the user's session cookie.
    
        -Application Starter Kits:
            Web laravel Authentication starter kits:
                -Laravel Breeze, Laravel Jetstream, and Laravel Fortify.

                -Laravel Breeze is a simple, minimal implementation of all of Laravel's authentication features, including login, registration, password reset, email verification, and password confirmation. Laravel Breeze's view layer is comprised of simple Blade templates styled with Tailwind CSS.

            API laravel Authentication starter kits(Laravel's API Authentication Services):
                -authenticating requests made with API tokens: Passport and Sanctum.
    --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------config/auth.php:
        return [
            'defaults' => [
                'guard' => 'web',
                'passwords' => 'users',
            ],

            -our default guard is web

            'guards' => [
                'web' => [
                    'driver' => 'session',
                    'provider' => 'users',
                ],
            ],

            -web driver is session : user data is stored using session
            -provider = users : link to database through users table

            'providers' => [
                'users' => [
                    'driver' => 'eloquent',
                    'model' => App\Models\User::class,
                ],
            ],

            -users driver to database through users table by using eloquent model
            -users model :name of model

            'passwords' => [
                'users' => [
                    'provider' => 'users',
                    'table' => 'password_resets',
                    'expire' => 60,
                    'throttle' => 60,
                ],
            ],
            -password :related to rest passwords
            -users provider :we can reset passwords for users table
            -'table' => 'password_resets' : using password resets table
            -throttle = 60 : number of times we can reset user password
            

            'password_timeout' => 10800,
        ];
    --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    Multi Auth Laravel:    

Different Methods:
    Requests & Input:
        -request::get(""):
        -request::has(""):
        -
*/