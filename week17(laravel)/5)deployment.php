<?php
/*
Deployment:
    -after getting host from host site(like: BlueHost)
    -enter cPanel
    -Email Acounts:
        -make email to be put in mail configuration
        -make password , and save it in a text file
        -after making email click on (connect devices) to get email configurations
    
    -Databases/MySQL DATABASE:
        -create database
        -make user
        -give user previlages
    
    -Upload my Project on github and clone it on server:
        git init
        git add *
        git commit -m "dsd"
        git push origin main
    
        
    link my subdomain on my project folder from Domains Folder
    -go to MultiPhpManager , select your subdomain , select php version


    .env :
        APP_NAME=Hospital
        APP_ENV=production
        APP_URL=https://blog.skillsdynamic.space/

        DB_DATABASE=acmewsmy_blog
        DB_USERNAME=acmewsmy_blog
        DB_PASSWORD=21zw)I45FLSK

        MAIL_HOST=blog.skillsdynamic.space
        MAIL_PORT=465
        MAIL_USERNAME=aih@blog.skillsdynamic.space
        MAIL_PASSWORD=21zw)I45FLSK
        MAIL_ENCRYPTION=ssl

    FileManager:
        in public_html Folder: clone my project

        -we need to set permissions for my project folder:
        -we will set permissions in storage folder in my project
            chmod -R 777 /home/acmewsmy/public_html/hospital/storage/

            -chmod : change mode
            - -R : means we can change in storage folder and its child folders
            - 777 : means we can read write delete , ... 
        

        -my host is vps(virtual private server) :can connect to terminal
        in terminal example: 
           cd /home/acmewsmy/public_html
           git clone myGitHubRepository
           cd /home/acmewsmy/public_html/hospital
           composer update 
           composer install
           yes
           
           -Note :
                if your are having problem with php version write this command to ignore it:
                    composer install --ignore-platform-reqs
            
            -To write .env file from terminal:
                touch .env  : will create .env file
                nano .env    :this will open file and i can page my local .env data
                ctrl + o  :to save file
                enter
                ctrl+x : to exit  
                
            chmod -R 777 /home/acmewsmy/public_html/hospital/storage/

            php artisan migrate:fresh --seed
            php artisan storage:link   : to make storage link 

    

           
*/