<?php
/*
User_Admin_Role:
    -we created new project (aih)
    -we want to make role in which admin only can go to dashboard page , and users can go to welcome page 

    Example :
        2014_10_12_000000_create_users_table.php:
            public function up()
            {
                Schema::create('users', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('email')->unique();
                    $table->timestamp('email_verified_at')->nullable();
                    $table->string('password');
                    $table->enum('role', ["admin", "user" ])->default("user");
                    $table->rememberToken();
                    $table->timestamps();
                });
            }
            -we added role column with user default
        -we created aih database : through phpmyadmin
        Models/User.php:
            protected $fillable = [
                'name',
                'email',
                'role',
                'password',
            ];

            -we added role to fillable array in order to be filled in create function

        UserSeeder.php:
            public function run()
            {
                $user = User::create([
                    'name'=>"admin",
                    'email'=>"admin@aih.com",
                    'role'=>'admin',
                    'password'=>Hash::make("123456789"),
                ]);
            }
            -we want to make admin by making seeder
        
        DatabaseSeeder.php:
            public function run()
            {
                $this->call([
                    UserSeeder::class,
                ]);

                // \App\Models\User::factory(10)->create();
            }
            -we added UserSeeder 
        -run seeds using :php artisan migrate:fresh --seed

        AuthenticatedSessionController.php:
            public function store(LoginRequest $request)
            {
                $request->authenticate();

                $request->session()->regenerate();

                if(auth()->user()->role == "admin"){
                    return redirect()->intended(RouteServiceProvider::HOME);
                }else{
                    return redirect("/");
                }
            }

            -we want to check if authenticated user is admin then go to  dashboard page, if authenticated user is user go to welcome page

        Middleware/IsAdmin.php:
            public function handle(Request $request, Closure $next)
            {
                if(auth()->user()->role == "admin"){
                    return $next($request);
                }else{
                    abort(403, "Require Admin Account");//throw error with messages
                }
            }

            -if the authenticated user(whose role is admin) , then admin can access dashboard, if
            the authenticated user(whose role is user) ,then he can not display dashboard page
            and will display error page with error message
        
        kernel.php:
            protected $routeMiddleware = [
                'auth' => \App\Http\Middleware\Authenticate::class,
                'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
                'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
                'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
                'can' => \Illuminate\Auth\Middleware\Authorize::class,
                'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
                'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
                'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
                'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
                'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
                "is_admin" =>  IsAdmin::class,
            ];

            -we did not add IsAdmin middleware to web group because it will be applied to all web routes , and we only wanted to apply it to specific route(dashboard)

        web.php:
            Route::get('/dashboard', function () {
                return view('dashboard');
            })->middleware(['auth', IsAdmin::class])->name('dashboard');

        welcome.blade.php:
            @auth
                @if(auth()->user()->role == "admin")
                    <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</a>
                @else
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a :href="route('logout')" onclick="event.preventDefault();
                        this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </a>
                    </form>
                    <a href="#" class="text-sm text-gray-700 dark:text-gray-500 underline">{{auth()->user()->name}}</a>
                @endif
            
            -if user role is admin , dashboard link appear 
            -else display name , logout link 

        RedirectIfAuthenticated.php:
            if (Auth::guard($guard)->check()) {
                // return redirect(RouteServiceProvider::HOME);
                if(auth()->user()->role == "admin"){
                    return redirect()->intended(RouteServiceProvider::HOME);
                }else{
                    return redirect("/");
                }
            }
            -if user tried to write login in the url , it will go to welcome page,
            -if admin tried to write login in the url , it will go to dashboard page,

*/  