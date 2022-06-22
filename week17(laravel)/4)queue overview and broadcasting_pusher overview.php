<?php
/*
Real time/broadcasting:
    -in web we work with http protocol (request and response)
    -Example:
        -in facebook ,if someone commented on a post , we don't need to reload page
        -so we will use ajax(through) javascript .
        -but in ajax we still make http request and receive response

    -Example:
        -in Notifications , we want if some user made something to send to me a notification
        -but the problem is that we don't know when to request the notification in order to appear to me
        -one solution is by using ajax ,we send request to server every minute to check if the user send a notification and wait for response 
        -so this will be bad because we are making too many requests
        
        best solution is that :when the user send a notification , server sends it to me 
        -so we are not making request and response
        -we will use something called web socket server

    Pusher:
        -if we need to work real time, we have a good service called pusher
        -pusher can work with any backend application not for laravel only
        -when we make fire(dispatch) event, event will talk to pusher, pusher listens at client 
        
        -broadcating is sent on chanels




BroadCasting:
    -real time
    -web socket server responsible to fire event on a chanel and wait for anyone to listen
    -so we need a good web socket server

    Web Socket server:
        -we can make our own web socket server using (Laravel Websocket) , has some problems
        -Pusher :
            -it gives me web socket server
            -real time messages
            -data will be with me 
        -firebase:
            -also gives me web socket server
            -real time messages
            -only difference is that data is on firebase(it has storage) not me
            -it is more suitable with frontend when they don't have database

    Pusher :
        -service for free 
        -gives me web socket server , which we will use with real time messages
        -we will make account on it
        -pusher is limited:
            -gives me 200k messages per day (100 concurrent connections users)

    How to Work with Pusher:
    Steps:
        -sign in
        -choose manage channels
        -create app
        -enter name of app
        -select a cluster(nearest server) : by default it will choose nearest server
        -choose your front (ex:jquery) , back(ex:laravel)
        -create app

        -download pusher library: composer require pusher/pusher-php-server
        -put these lines in .env:
            PUSHER_APP_ID=1426859
            PUSHER_APP_KEY=2f9d0c79972d4af3a884
            PUSHER_APP_SECRET=ccd8f6b3729d8bc043aa
            PUSHER_APP_CLUSTER=eu
        -Do not forget to add broadcast driver in .env :
            BROADCAST_DRIVER=pusher

        -config/broadcasting.php:
            'options' => [
                'cluster' => 'eu',
                'useTLS' => true
            ],
        
        -make event:
            -php artisan make:event HelloEvent

            HelloEvent.php:
                class MyEvent implements ShouldBroadcast
                {
                    use Dispatchable, InteractsWithSockets, SerializesModels;

                    public $message;

                    public function __construct($message)
                    {
                        $this->message = $message;
                    }

                    public function broadcastOn()
                    {
                        return ['my-channel'];
                    }

                    public function broadcastAs()
                    {
                        return 'Hello-Event';
                    }
                }
                
                - contructor will receive message that will be sent
                - broadcastOn() :name of channel to broadcast on
                - broadcastAs() :what (events) will be broadcasted 
                - we can broadcast multiple events on same channel 
                - don't forget to implements ShouldBroadcast 
                - we will not make listener for this event

        -dispatch the event

        -who will listen/receive to this event ?
            -there is a javascript script in frontend that we will copy 
            -this script can be put in my layout.blade or it can be in another project

            Example:
                we will make test-broadcast.html outside laravel project and copy the code to it 
                test.html:
                    <!DOCTYPE html>
                    <head>
                    <title>Pusher Test</title>
                    <script src="https://js.pusher.com/7.1/pusher.min.js"></script>
                    <script>

                        // Enable pusher logging - don't include this in production
                        Pusher.logToConsole = true;

                        var pusher = new Pusher('2f9d0c79972d4af3a884', {
                        cluster: 'eu'
                        });

                        var channel = pusher.subscribe('my-channel');
                        channel.bind('my-event', function(data) {
                        alert(JSON.stringify(data));
                        });
                    </script>
                    </head>
                    <body>
                    <h1>Pusher Test</h1>
                    <p>
                        Try publishing an event to channel <code>my-channel</code>
                        with event name <code>my-event</code>.
                    </p>
                    </body>


                    explaination:
                        var pusher = new Pusher('2f9d0c79972d4af3a884', {
                        cluster: 'eu'
                        });

                        -we made object from pusher and gave it PUSHER_APP_KEY and PUSHER_APP_CLUSTER

                        var channel = pusher.subscribe('my-channel');
                        channel.bind('Hello-Event', function(data) {
                        alert(JSON.stringify(data));
                        });

                        -Listens : subscribe to the channel
                        -give it name of event, receive data coming from event
                        -display data in an alert message
        Testing Broadcasting:
            -we can fire event using php tinker and using this code:
                event(new \App\Events\HelloEvent('hello world'));


                        
*/  