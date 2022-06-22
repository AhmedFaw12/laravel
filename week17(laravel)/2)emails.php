<?php
/*
Emails:
    Mailable class:
        -mail shapes(blades) that will be sent
        -or markdown mail
        -mail blade is html file with css , bootstrap , can be dynamic 
        -to make my mail class that will extends mailable class:
            php artisan make:mail MyClassName
        -my mailable class will be created in app/Mail folder
        
        mail shapes can be :
            -blades
            -markdown mail used for readme.md of projects

    Mail class:
        -responsible for sending the mail


    Example :
        -php artisan make:mail WelcomeMail : to create my mailable class

        .env:
            //# my mailtrap code
            MAIL_MAILER=smtp
            MAIL_HOST=smtp.mailtrap.io
            MAIL_PORT=587
            MAIL_USERNAME=6538f0b6ea0296
            MAIL_PASSWORD=bdf37901b8b7c2
            MAIL_ENCRYPTION=tls
            MAIL_FROM = team@aih.com

            -setting mailtrap to be my mail server
            -setting from who email will be sent

        WelcomeMail.php :
            class WelcomeMail extends Mailable
            {
                public $user;
                public function __construct($user)
                {
                    $this->user = $user;
                }

                public function build()
                {
                    return $this->from(env("MAIL_FROM"))->view('my_emails.welcome');
                }
            }

            -build function will be called automatically
            -from method to determine from who email will be sent
            -build method will call the mail view blade 

            -to send dynamic data to mail view/blade : 
                -set properties before construct 
                -these parameter will be sent automatically to mail blade
            
        -my_emails/welcome.blade.php:
            <body>
                <h1>Welcome {{$user->name}}</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam porro quas, eligendi voluptatibus commodi inventore consequuntur consectetur at obcaecati possimus deleniti, impedit harum, ducimus illum praesentium suscipit ullam eveniet dolorum.</p>

                <ul>
                    <li>Mobile : {{$user->mobile}}</li>

                </ul>
                <h4>Thanks</h4>
            </body>
        -RegisteredUserController.php:
            public function store(Request $request)
            {
                Mail::to($request->email)->send(new WelcomeMail($user));           
            }

            - use Mail class to send email 
            - to method to set to whom the email will be sent
            - in send method ,we will make new object from our mailable class for the email to be sent
    ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    Markdown(md) :
        -it is a language similar to html
        
        -هى لغة توصيفية
        -مجموعة من الاكواد لو استخدمتها مع اى تيكست \نص ,اقدر اتلاعب فى النص دا
        
        Generating Markdown Mailables:
            -php artisan make:mail myMarkdownClass --markdown=myFolder.myMardownName

            -To generate a mailable with a corresponding Markdown template, you may use the --markdown option of the make:mail Artisan command:

            Then, when configuring the mailable within its build method, call the markdown method instead of the view method. The markdown method accepts the name of the Markdown template and an optional array of data to make available to the template:

            example :
            public function build()
            {
                return $this->from('example@example.com')
                            ->markdown('emails.orders.shipped', [
                                'url' => $this->orderUrl,
                            ]);
            }
        
        Writing Markdown Messages:
            Markdown mailables use a combination of Blade components and Markdown syntax which allow you to easily construct mail messages while leveraging(benifits from) Laravel's pre-built email UI components:

            example :
                @component('mail::message')
                # Order Shipped
                
                Your order has been shipped!
                
                @component('mail::button', ['url' => $url])
                View Order
                @endcomponent
                
                Thanks,<br>
                {{ config('app.name') }}
                @endcomponent

            Button Component:
                The button component renders a centered button link. The component accepts two arguments, a url and an optional color. Supported colors are primary, success, and error. You may add as many button components to a message as you wish:

                @component('mail::button', ['url' => $url, 'color' => 'success'])
                View Order
                @endcomponent
        
        ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        
        Example: 
            -we want when user delete department ,it will send email to  admin@aih.com 
            that the department has been deleted

            -php artisan make:mail DeletedDepartment  --markdown=my_mails.dept_delete

            DeletedDepartment.php:
                class DeletedDepartment extends Mailable
                {
                    private $department;
                    public function __construct($department)
                    {
                        $this->department = $department;
                    }

                
                    public function build()
                    {
                        return $this->from("app@aih.com")->markdown('my_mails.dept_delete')
                        ->with("department", $this->department);
                    }
                }
            my_mails/dept_delete.blade.php:
                @component('mail::message')
                # Hello

                user {{auth()->user()->name}} delete {{$department->name}} at {{now()}}

                {{-- env("APP_URL") .'/department'--}}
                @component('mail::button', ['url' => route('department.index')])
                To check it
                @endcomponent

                Thanks,<br>
                {{ config('app.name') }}
                @endcomponent

            DepartmentController.php:
                public function destroy(Department $department)
                {
                    // dd($department->url);
                    Storage::delete($department->url);//delete image from storage folder
                    $department->delete();//delete from database


                    Mail::to("admin@aih.com")->send(new DeletedDepartment($department));

                    // return $department; //can be return as an object
                    return redirect()->route("department.index");

                }


*/