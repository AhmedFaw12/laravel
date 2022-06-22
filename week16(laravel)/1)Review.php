<?php
/*
when the user login , server creates a token for user and save it in tokens table , 
then server send this token to the user , the token is saved in the user client in local storage of the browser

-Laravel Passport:
    -another package to authorize api token harder than sanctum
    -It can authenticate users on another app (not my app) like :google , facebook
    -we use google credentials to login on another app
    -we don't have account on my app , but we will login with google account authentication
    -so we will use passport(وسيط) , we will tell passport that we have google user , so it is safe to login to my app

-Laravel Socialite:
    -In addition to typical, form based authentication, Laravel also provides a simple, convenient way to authenticate with OAuth providers using Laravel Socialite. Socialite currently supports authentication via Facebook, Twitter, LinkedIn, Google, GitHub, GitLab, and Bitbucket.

*/