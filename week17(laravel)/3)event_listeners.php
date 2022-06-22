<?php
/*
Events and Listeners:
    Events:
        -when an action happens it will fire an event
    
    Listener:
        -listener listens to an event
        -if event is fired
        -listener will perform certain action

    when to use Event and Listener:
        -if there is a repeatable code , so we will put this code in the listener 
        and fire an event so that the listener perform this code
    
    How to Generate Events and Listeners:
        First Method:
            generate event : 
                php artisan make:event MyNameEvent

                -my event is created in app/Events
            generate listener :
                php artisan make:listener MyNameListener

                -my listener is created in app/Listenes
            link events with listeners:
                app/providers/EventServiceProvider:
                    protected $listen = [
                        DeleteDeptEvent::class =>[
                            DeleteDeptActionListener::class,
                        ],

                       //
                    ];

                    -event has array of listeners
                    -event can have multiple listeners
        ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------

        Second Method:
            link events with listeners with full path:
                protected $listen = [
                    DeleteDeptEvent::class =>[
                        DeleteDeptActionListener::class,
                    ],

                    "App\Events\LoginEvent" =>[
                        "App\Listeners\LoginListener",
                    ],
                ]                    
            -generate both event and listener files using this command:
                php artisan event:generate
    ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    How to fire Event:
        MyEventName::dispatch(anyDatasent);
        
        -example : DeleteDeptEvent::dispatch($department);
    
    ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    Full Example:
        -generate event
        -generate listener
        app/providers/EventServiceProvider:
            protected $listen = [
                DeleteDeptEvent::class =>[
                    DeleteDeptActionListener::class,
                ],
            ];
            -link event with its listener
        
        DepartmentController.php:
            public function destroy(Department $department)
            {
                Storage::delete($department->url);//delete image from storage folder
                $department->delete();//delete from database
                
                //firing event
                DeleteDeptEvent::dispatch($department);

                // return $department; //can be return as an object
                return redirect()->route("department.index");

            }

            -fire event and pass parameters to it

        DeleteDeptEvent.php:
            public $department;
            public function __construct($department)
            {
                $this->department = $department;
            }
            
            -accept parameters and sending it automatically to listener

        DeleteDeptActionListener.php:
            public function handle($event)
            {
                Mail::to("admin@aih.com")->send(new DeletedDepartment($event->department));
            }
            
            -parameter sent will be in $event object
            -listener will send email
        

                
*/
