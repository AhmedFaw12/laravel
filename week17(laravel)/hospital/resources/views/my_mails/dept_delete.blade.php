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
