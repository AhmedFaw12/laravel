<?php
/*
Spatie :
    Introduction:
        -Spatie provide a powerful roles and permissions package for Laravel. it's a great way to manage complete roles each with their own permissions.

        -we don't need to make role column in users table
        -we don't IsAdmin middleware that we made before
    ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    Installation:
        1) You can install the package via composer: 
            - composer require spatie/laravel-permission

        2)Optional: The service provider will automatically get registered. Or you may manually add the service provider in your config/app.php file:
            'providers' => [
                // ...
                Spatie\Permission\PermissionServiceProvider::class,
            ];

        3)Publish the migrations and config file: 
            - php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

            -this will publish the migration and the config/permission.php config file
            -output :
                Copied File [/vendor/spatie/laravel-permission/config/permission.php] To [/config/permission.php]
                Copied File [/vendor/spatie/laravel-permission/database/migrations/create_permission_tables.php.stub] To [/database/migrations/2021_12_22_111730_create_permission_tables.php]
                Publishing complete.
            
            -it will create migration called (create_permission_tables) that will create 5 tables: roles, permissions , model_has_permissions, model_has_roles, role_has_permissions

            -it will create permission.php in config folder:
                -'teams' => true : if we want to use teams property set it to true
                -tablenames array : if we want to change table names values
        4)clear the cache:
            - php artisan optimize:clear
                # or
            - php artisan config:clear
        
        5)Run the migrations: After the config and migration have been published and configured, you can create the tables for this package by running:
            -php artisan migrate
        
    ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    How to Use/give Permissions/roles:
        -laravel contains by default something called (gate, permissions, can)
        -Gates:
            -Gates are simply closures that determine if a user is authorized to perform a given action. Typically, gates are defined within the boot method of the App\Providers\AuthServiceProvider class using the Gate facade. Gates always receive a user instance as their first argument and may optionally receive additional arguments such as a relevant Eloquent model.
        -can:
            -there is blade directive called can
            -there is also can middleware ,which is used to apply certain permission on route

            example on can middleware : web.php:
                Route::get('/dashboard', function () {
                    return view('dashboard');
                })->middleware(['auth', "can:access dashboard"])->name('dashboard');
            example on can directive:
                @can("permissionName")
                    ------
                @endcan
        -spatie uses all these three : gate, permission, can

        -we should create 5permissions(add,edit,delete,show ,show all ) for each table in database :
            example 
                for user:
                    add user
                    edit user
                    delete user
                    show user
                    show all user

        Example:
            Models/User.php:
                use Spatie\Permission\Traits\HasRoles;
                use HasRoles;  

                -user need to use HasRoles trait ,if we want user to get roles and permissions

            PermissionSeeder.php:
                use Spatie\Permission\Models\Permission;
                public function run()
                {
                    Permission::create(["name" => "access dashboard"]);
                    Permission::create(["name" => "register"]);
                }

                -we created multiple permissions in permissions table
            
            PermissionToUserSeeder.php:
                public function run()
                {
                    $user = User::find(1);
                    $user->givePermissionTo("access dashboard");
                }

                -we gave user whose id 1 permission called(access dashboard)
            RoleSeeder.php:
                use Spatie\Permission\Models\Role;

                public function run()
                {
                    $r = Role::create(["name" =>"admin"]);

                    $r->givePermissionTo("access dashboard");
                    $r->givePermissionTo("register");

                    $user = User::find(1);
                    $user->assignRole("admin");

                    $r = Role::create(["name" =>"customer"]);
                    $r = Role::create(["name" =>"doctor"]);
                }
                -we create roles (admin, customer, doctor)
                -we can give role multiple permissions 
                -then we can assign the role to certain user
                
            web.php:
                Route::get('/dashboard', function () {
                    return view('dashboard');
                })->middleware(['auth', "can:access dashboard"])->name('dashboard');

                -only user who has access dashboard permission can go to dashboard page

            -DatabaseSeeder.php:
                $this->call([
                    UserSeeder::class,
                    PermissionSeeder::class,
                    RoleSeeder::class,
                    PermissionToUserSeeder::class
                ]);

                -we added our seeders 
    ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    -Super Admin:
        Gate::before  :
            If you want a "Super Admin" role to respond true to all permissions, without needing to assign all those permissions to a role, you can use Laravel's Gate::before() method. For example:
            
            Example:
                AuthServiceProvider.php:
                    use Illuminate\Support\Facades\Gate;

                    class AuthServiceProvider extends ServiceProvider
                    {
                        public function boot()
                        {
                            $this->registerPolicies();

                            // Implicitly grant "Super Admin" role all permissions
                            // This works in the app by using gate-related functions like auth()->user->can() and @can()
                            Gate::before(function ($user, $ability) {
                                return $user->hasRole('Super Admin') ? true : null;
                            });
                        }
                    }
                
                RoleSeeder.php:
                    public function run()
                    {
                        //creating super Admin role
                        $super_admin = Role::create(["name"=>"Super Admin"]);
                        $user->assignRole("Super Admin");
                       
                        $user = User::find(1);

                        //
                    }
        




        
*/      