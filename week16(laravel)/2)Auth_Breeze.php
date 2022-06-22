<?php
/*
Laravel Breeze :
    Introduction:
        -it is a laravel package/module/library that makes me Login , Register , email verification, password confirmation with their authentication. it offers me views styled with tailwind css
        
        -To give you a head start building your new Laravel application, we are happy to offer authentication and application starter kits. These kits automatically scaffold/fill your application with the routes, controllers, and views you need to register and authenticate your application's users.
        
        -While you are welcome to use these starter kits, they are not required. You are free to build your own application from the ground up by simply installing a fresh copy of Laravel. Either way, we know you will build something great!
        
        -Laravel Breeze is a minimal, simple implementation of all of Laravel's authentication features, including login, registration, password reset, email verification, and password confirmation. Laravel Breeze's default view layer is made up of simple Blade templates styled with Tailwind CSS.
    ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    Installation:
        Commands steps:
            1)composer require laravel/breeze --dev
            2)php artisan breeze:install 
            3)npm install
            4)npm run dev
            5)npm run watch

        Explanation:
            -composer require laravel/breeze --dev  : 
                -install Laravel Breeze using Composer
                -first we download breeze
                -By default : breeze package is not in vendor folder so we should download it
            -php artisan breeze:install :
                -This command publishes the authentication views, routes, controllers, and other resources to your application. 
                -Laravel Breeze publishes all of its code to your application so that you have full control and visibility over its features and implementation.
             
            -npm install : 
                -this command will download node_modules folder (node packages) based on package.json  .
                -The default package.json file already includes everything you need to get started using Laravel Mix. 
            -npm run dev:
                -run laravel Mix
                -all my UI libraries combines them all in one css and js folders and put in public folder.
            -npm run watch:
                -The npm run watch command will continue running in your terminal and watch all relevant CSS and JavaScript files for changes. Webpack will automatically recompile your assets when it detects a change to one of these files.

                -If you are lazy and don’t want to run the npm run dev command every time you make changes in SASS and JS file, you should use the following command.
        --------------------------------------------------------------------------------------------------------------------------------------------------------------------------

        Notes:
            -we will only build our ui once 
            -node_modules will not be uploaded on server/github
            -we don't need node_modules on server as we already created css and js minified in public folder
    ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    What files and routes will be Created :
        Routes folder:
            -web.php:
                Route::get('/dashboard', function () {
                    return view('dashboard');
                })->middleware(['auth'])->name('dashboard');

                require __DIR__.'/auth.php'; 
                
                - require __DIR__.'/auth.php': will go to auth.php
                - __DIR__ : will get the current working directory
            -auth.php :
                -contain routes for login, registeration , verifications, ...
                -for login and registeration : we will create 2 routes (get , post) for each
        Controller folder:
            -Auth folder:contain controller files to execute functions called in auth routes
        Resources folder:
            Views folder:
                -breeze created:
                -dashboard blade:
                -auth folder :
                    -breeze created blades/UI for login, register, forget-password, verify-email, reset-password, confirm-password

                    -example:
                        -login blade uses guest layout
                        -we passed another component(auth-card) as slot content
                        -auth-card : contain two slots (logo, slot)
                        -in login blade :in authcard component we passed logo and the rest is slot value
                -Layouts:
                    -breeze made main component layouts:
                        -guest layout:
                            -used by auth blades 
                        -app layout:
                            -when we login (dashboard)
                        -navigation layout:
                            this is the navigation bar 
                            -we can add links in it
                -Components folder
    ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    Examples Testing Breeze:
        -register :
        -Login:
        -forget your Password:
            -we need to set .env file to work with mailtrap fake server :
                1)go to mailtrap.com
                2)choose laravel 7+
                3)copy this code :
                    MAIL_MAILER=smtp
                    MAIL_HOST=smtp.mailtrap.io
                    MAIL_PORT=587
                    MAIL_USERNAME=6538f0b6ea0296
                    MAIL_PASSWORD=bdf37901b8b7c2
                    MAIL_ENCRYPTION=tls
                4)make port = 587
                5)replace similar code in .env with this code
                6)use forget password in laravel breeze ui
    --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    If we want to use Bootstrap :
        -npm install boostrap : install/download bootstrap files in node_modules folder 
        -go to resources/css/app.css:
            -import 'bootstrap'
        -npm run dev

        Notes:
            1)
                -UI will look bad , because laravel breeze is build depend on tailwind
                -so i should get another UI that depend on bootstrap like (laravel UI package)
            2)
                - if i have any external css , put it in public/css 
    --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    Full Example:
        web.php:
            Route::prefix("/users")->middleware("auth")->group(function(){
                Route::get("/", function(){
                    return view("users.list");
                })->name("users.list");

                //other routes
            });
            -we added route for users and applied auth middleware on it

        navigation.blade.php:
            <x-nav-link :href="route('users.list')" :active="request()->routeIs('users.list')">
                {{ __('Users') }}
            </x-nav-link>

            -we added link for users view in nav bar
        resources/views:
            Users/list.blade.php:
                <x-app-layout>
                    <x-slot name="header">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ __('User') }}
                        </h2>
                    </x-slot>

                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 bg-white border-b border-gray-200">
                                    List of Users
                                </div>
                            </div>
                        </div>
                    </div>
                </x-app-layout>

                -we made view for user.list , we copied dashboard layout to it and changed some names 
    ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    Node.js:
        -npm(node package manager) : 
            -same as composer but for js
            -it is package manager for js
            -it can get me bootstrap, tailwind, ...
            -npm install : will download all packages in package.json
    -Compiling Assets (Mix):
        Introduction:
            -Laravel Mix, a package developed by Laracasts creator Jeffrey Way.
            
            -Mix makes it a cinch/easy to compile and minify your application's CSS and JavaScript files. Through simple method chaining

        -Installing Node:
            -Before running Mix, you must first ensure that Node.js and NPM are installed on your machine:
                node -v
                npm -v
            -we can download Node from the official Node website : windows Installer 64 bits
        
        -Installing Laravel Mix:
            -The only remaining step is to install Laravel Mix. Within a fresh installation of Laravel, you'll find a package.json file in the root of your directory structure. 
            
            -The default package.json file already includes everything you need to get started using Laravel Mix. 
            
            -Think of this file like your composer.json file, except it defines Node dependencies instead of PHP dependencies. You may install the dependencies it references by running:
                -npm install

            -this command will download node_modules folder (node packages)
            based on package.json
            -node_modules folder will not be uploaded on github as it is in gitignore list
        
        -Running Mix:
            -all my UI libraries combines them all in one css and js folders
            and put in public folder.

            -Mix is a configuration layer on top of webpack, so to run your Mix tasks you only need to execute one of the NPM scripts that are included in the default Laravel package.json file. 
            
            -When you run the dev or production scripts, all of your application's CSS and JavaScript assets will be compiled and placed in your application's public directory:
                -npm run dev
    --------------------------------------------------------------------------------------------------------------------------------------------
    Working With StyleSheets:
        Working With Tailwind CSS:
            -Tailwind is a framework like Bootstrap
            -Tailwind CSS is written in JavaScript and distributed as an npm package, which means you've always had to have Node.js and npm installed to use it.

            -Tailwind is made by node 
            -so we should install Tailwind using NPM
            -and generate our Tailwind configuration file:
            1)commands:
                npm install
                npm install -D tailwindcss
                npx tailwindcss init
            
            -The init command will generate a tailwind.config.js file. The content section of this file allows you to configure the paths to all of your HTML templates, JavaScript components, and any other source files that contain Tailwind class names so that any CSS classes that are not used within these files will be purged from your production CSS build:

            -Add the paths to all of your template files in your tailwind.config.js file.
                
                content: [
                    './storage/framework/views/*.php',
                    ........
                ],

                -Note : laravel breeze install command generates  tailwind.config.js file by default

            2)Next, you should add each of Tailwind's "layers" to your application's resources/css/app.css file:
                @tailwind base;
                @tailwind components;
                @tailwind utilities;
    
            3)Once you have configured Tailwind's layers, you are ready to update your application's webpack.mix.js file to compile your Tailwind powered CSS:

                mix.js('resources/js/app.js', 'public/js')
                    .postCss('resources/css/app.css', 'public/css', [
                        require('tailwindcss'),
                    ]);

            4)Finally, you should reference your stylesheet in your application's primary layout template. Many applications choose to store this template at resources/views/layouts/app.blade.php. In addition, ensure you add the responsive viewport meta tag if it's not already present:
                <head>
                    <meta charset="UTF-8" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                    <link href="/css/app.css" rel="stylesheet">
                </head>
            
            -Note : laravel breeze install command generates  tailwind.config.js file and do all of the above steps by default 
    --------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    
        
*/           

