<p align="center"><h1>YourSurprise assignment: Road inspector<h1></p>

## About

This project is build using [Laravel 9](https://laravel.com/), php 8, mysql 8 and [MapBox](https://www.mapbox.com/).

## Requirements
- [Composer, prefferably installed globaly](https://getcomposer.org/download/)
- [PHP 8.* (Should come with wamp if you are using a local development server)](https://www.php.net/downloads)
- [MySql 8.* (Should come with wamp if you are using a local development server)](https://www.mysql.com/)
- [MapBox public access token](https://www.mapbox.com/)

If used local:
- A computer 🙃 (Windows or Linux)
- [wamp or any similiar local development servers if you are using windows](https://www.wampserver.com/en/download-wampserver-64bits/)

If installed remote:
- [A server prefferably configured with linux](https://www.ibm.com/nl-en/it-infrastructure/servers?utm_content=SRCWW&p1=Search&p4=43700068028831511&p5=p&gclid=CjwKCAjw0a-SBhBkEiwApljU0rJ6KR7_kgbR2_Y5UAEfMzWWJyQ6myY4FJVOGwVp6wt1HS-YOJOrZhoCy08QAvD_BwE&gclsrc=aw.ds)

## How to setup

Note: If you are using Windows Wamp should come with PHP 8 and MySQL 8 if you are going to setup the project locally. If not, download MySQL 8 and PHP 8 and add them to your environment variables in windows. Check how it's done for [PHP](https://www.php.net/manual/en/faq.installation.php) and for [MySQL](https://stackoverflow.com/questions/5920136/mysql-is-not-recognised-as-an-internal-or-external-command-operable-program-or-b).
If you wish to not restart your machine every time you switch between PHP and MySQL versions, you can install [Chocolatey](https://chocolatey.org/install) and run the following command: ```refreshenv```

If you are using ubuntu follow this [guide](https://linuxhint.com/install_apache_web_server_ubuntu/) to configure your machine to act as a webserver or use [nginx](https://laravel.com/docs/9.x/deployment) for the deployment of Laravel

<br>*Windows only:*
<br>- **Step 1**: Install a local development server (Like e.g. Wamp).
<br>- **Step 2**: Start your local development server.
<br>*End Windows only*
<br>- **Step 3 **: Install MySQL 8 and PHP 8 if not already done or skipped step 1 and 2. To check the versions use the following commands:
<br>PHP: ```php -v```
<br>MySQL: ```mysql -v``` or ```mysql --version```
<br>If the commands don't show the required versions, check the requirements to download the correct version and the notes mentoined above to set the environment variables.
<br>Note: Sometimes the neccesary PHP extension are not pre installed. To see how you can install those check this [guide (ubuntu)](https://computingforgeeks.com/how-to-install-php-on-ubuntu-2/) on how to install them. *If you are not sure which ones you need to install, later on in this guide ```composer install``` will show you which ones you are missing. Most likely the missing extensions will be xml, mbstring and PDO*.
<br>- **Step 4**: Install composer.
<br>- **Step 5**: Pull the master branch. If you're using Linux and wish to use a more permanent solution, make sure your Nginx configuration is set to the location where you pull the project to.
<br>- **Step 6**: run the following command in your projects folder where the composer.json lives: ```composer install``` and wait a few minutes
<br>- **Step 7**: Go in your PHPMyAdmin panel (if installed), localhost/phpmyadmin, and create a database. If you are using the CMD/Terminal use the following commands:
<br>```mysql -u username -p```
<br>*Enter your password if set*
<br>```CREATE DATABASE [IF NOT EXISTS] database_name COLLATE utf8mb4_unicode_ci```
<br>*Use this collate to make sure your database can store an arrangement of different unuasal charachters*
<br>- **Step 8**: copy .env.example and rename to .env (Never ever ever ever push the .env file to your repository)
<br>- **Step 9**: Change the following env vars:
   
   - APP_NAME (if you wish to do so)
   - APP_ENV to production if you are going to make it public
   - APP_DEBUG to false for the same reason
   - Change the DB env vars to your database connection
   - API_KEY to the key provided by the ANWB
   
<br>- **step 10**: Run the following command (given you are still in the projects folder) ```php artisan key:generate```
<br>- **Step 11**: Run ```php artisan migrate```
<br>- **step 12**: Create an account on [MapBox](https://www.mapbox.com/) and generate a public access token in the Tokens page.
<br>- **step 13**: Copy this token and paste it in public/js/main.js by replacing ```[your-token]```

This concludes the basic setup. Now we need to setup the cronjobs.
<br> All you have to do is run the following command: ```php artisan schedule:work```
<br> In this project the schedule is set to run every 5 minutes. If you wish to change the frequency or what the schedule does, check the Kernel.php found in app > Console and the following [documentation](https://laravel.com/docs/9.x/scheduling#defining-schedules).

If you want a more permanent solution to make the cronjob run, this requires a bit of editing.
- Go to routes > api.php. Uncomment the API route that contains the route ```/anwb/getData``` 
- This is a bit unsecure, so to combat this generate a key of atleast 16 charachters long.
- Go to the .env file and add the key to the env var LOCAL_KEY.
- Next up go to app > Htpp > Controllers > anwbApiController. Uncomment the if statement and add ```Request $request``` to the function getData.

The first function should now look like this:
``` 
    public function getData(Request $request)
    {

        if($request['key'] !== env('LOCAL_KEY')){
            Log::channel('dataProcess')->warning('Unauthorised');
            return;
        }
```
- To add the cronjob run this command in the terminal: ```crontab -e```
- Type: ```*/5 * * * * curl -X GET -d domain-or-localhost/api/anwb/getData?key=[yourGeneratedKey]``` and save your file by pressing ctrl+x, type y and press enter.
- Make sure your cron service is running, you can check this by using the following command: ```systemctl status cron```. If the status says inactive run: ```systemctl start cron```

The cronjob should now be added to your crontab and will run every 5 minutes.

<br>To run the project simply run (given you are still in the laravel folder): ```php artisan serve``` and navigate to localhost:8000.
<br>If you wish to use a more permanent solution, the project should work automatically if Nginx is configured correctly shown in this [guide](https://laravel.com/docs/9.x/deployment).
<br>It might take a few minutes before the project shows data. This is because we set the cronjob to run every 5 minutes and we might not have any data in the first few minutes.

Note: if you are getting SSL error messages in storage/logs/laravel.log or storage/logs/api.log, edit the following line in app/Http/Controller/anwbApiController.php
```curl_setopt($ch, CURLOPT_CAINFO, $_SERVER['DOCUMENT_ROOT'] . "../cacert.pem");``` to where your server SSL certificate lives or download it from [here](https://curl.se/docs/caextract.html) and place it in your projects folder or copy it to /etc/ssl/certs by running: ```sudo wget https://curl.se/ca/cacert.pem && sudo mv cacert.pem /etc/ssl/certs/```

