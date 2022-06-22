<?php

/*
Continue Model Relationships:
    -one_To_Many:
        -Example:
            -specification_type has many specifications(color = red or yellow or ...)
            -specification belong to one specification_type
    -Polymorphic Relationships:
        -One To Many (Polymorphic):
            Table Structure:
                A one-to-many polymorphic relation is similar to a typical one-to-many relation; however, the child model can belong to more than one type of model using a single association. For example, imagine users of your application can "comment" on posts and videos. Using polymorphic relationships, you may use a single comments table to contain comments for both posts and videos. First, let's examine the table structure required to build this relationship:

                posts
                    id - integer
                    title - string
                    body - text
            
                videos
                    id - integer
                    title - string
                    url - string
                
                comments
                    id - integer
                    body - text
                    commentable_id - integer
                    commentable_type - string
            
            -Model Structure:
                Next, let's examine the model definitions needed to build this relationship:

                namespace App\Models;
                
                use Illuminate\Database\Eloquent\Model;
                
                class Comment extends Model
                {
                   
                    //Get the parent commentable model (post or video).
                     
                    public function commentable()//function named after first section of column name (commentable_type , commentable_id)
                    {
                        return $this->morphTo();
                    }
                }
                
                class Post extends Model
                {
                    
                    //Get all of the post's comments.
                     
                    public function comments()
                    {
                        return $this->morphMany(Comment::class, 'commentable');//related class , function defined in Comment model
                    }
                }
                
                class Video extends Model
                {
                    //Get all of the video's comments.
                   
                    public function comments()
                    {
                        return $this->morphMany(Comment::class, 'commentable');
                    }
                }
            Example :
                -ev has many specifications 
                -also pv has many specifiactions 
                -so instead of making 2 specification tables (one for ev, one for pv)
                -we will make one specification table for both (using polymorphic relationship (one to many))

                ev Model:
                    function specifications(){
                        return  $this->morphMany(Specification::class, "model");//related class , function defined in specification model
                    }
                specification Model:
                    function model(){//function named after first section of column name (model_type , model_id)
                        return $this->morphTo();
                    }
        ------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    We need to Test Our Tables Using Factories & Seeders :
        
        UserFactory:
            -we will create bulk(many) data :
                return [
                    'first_name' => $this->faker->name(),
                    'last_name' => $this->faker->name(),
                    'name' => $this->faker->name(),
                    'email' => $this->faker->unique()->safeEmail(),
                    'mobile' => $this->faker->unique()->phoneNumber(),
                    'email_verified_at' => now(),
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    'remember_token' => Str::random(10),
                ];
        
        UserSeeder:
            -we need to create user who has role = super-admin
            public function run()
            {//we want to make super admin
                $user = new User();
                $user->first_name = "admin";
                $user->last_name = "super";
                // $user->password = Hash::make("123456789");
                $user->password = bcrypt("123456789");
                $user->email = "ahmed@gmail.com";
                $user->mobile = "01006654568";
                $user->save();
            }
        
        EvManufacturerFactory:
            public function definition()
            {
                return [
                    'name' => $this->faker->word(),
                    'updated_by' =>1,
                ];
            }
        EvModelFactory:
            public function definition()
            {
                return [
                    'name' => $this->faker->word(),
                    'ev_manufacturer_id' => EvManufacturer::get()->random()->id,
                    'updated_by' =>1,
                ];
            }
        EvFactory:
            public function definition()
            {
                return [
                    "battery_capacity" =>$this->faker->numberBetween([1000,1500]),
                    'ev_model_id' => EvModel::get()->random()->id,
                    'user_id' => User::get()->random()->id,
                    'updated_by' =>1,
                ];
            }
        SpecificationTypeFactory:
            public function definition()
            {
                return [
                    "name"=> $this->faker->word(),
                    "updated_by" =>1,
                ];
            }
        SpecificationFactory:
            public function definition()
            {
                return [
                    "body"=> $this->faker->word(),
                    "model_type" =>"App\Models\Ev",
                    "model_id" => Ev::get()->random()->id,
                    "specification_type_id" => SpecificationType::get()->random()->id,
                    "updated_by" =>  1,
                ];
            }   
        DatabaseSeeder.php:
            -we will call all seeders and Factories here 
            -seeders are called using call() methed and pass seeders in an array 
            -factories are called under call() method using factory of model class and create() method

            public function run()
            {
                $this->call([
                    UserSeeder::class,
                ]);
                User::factory(10)->create();
                EvManufacturer::factory(10)->create();
                EvModel::factory(50)->create();
                Ev::factory(150)->create();
                SpecificationType::factory(5)->create();
                Specification::factory(500)->create();
            }
        
        -use this command to run all factories and seeders :  php artisan db:seed

        -To create migration and seeder , factories at same time : php artisan migrate:fresh --seed
    Artisan Tinker:
       - All Laravel applications include Tinker by default

       -Tinker allows you to interact with your entire Laravel application on the command line, including your Eloquent models, jobs, events, and more.
       
       -For example you can test any query you want 

       - To enter the Tinker environment , run the tinker Artisan command:
            php artisan tinker

       -Example:
            User::get()->random()->id
            $user = User::find(2)
            $user = User::evs
            $ev = Ev::find(5)
            $ev->ev_model
            $ev->ev_model->ev_manufacturer
            $ev->ev_model->ev_manufacturer->ev_models
            $ev->user
            $u = User::find(3)
            $u->evs

            $s = Specification::find(150)
            $s->model



 
    
    Methods and Functions :
        1)get()
        2)random()
        3)save()
        4)create()
        5)find()
        5)bcrypt()
        6)Hash::make()
        7)all()
    Faker Methods :
        1)name()
        2)word()
        3)numberBetween()
        4)unique()
        5)safeEmail()
        6)phoneNumber()
        7)now()
*/