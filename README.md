# Logs Import into DB and APIs

A simple laravel pseudo-microservices logs import/read project.
This is NOT a real "microservices" setup or at least something that is production ready!


This project consists of two services `micrologs`, `logscount`.

Set up
------------

#### Clone Repository

`git clone git@github.com:mealyus/microservices.git`

#### Composer install

`composer install`

#### Run the migration command

#### Place logs file

`Place your logs.txt file in storage/app/public directory`

#### Command to import logs into database
```
    php artisan import:micrologs
```

#### API Request to get logs count with filters
```
    # Get the logs
    Logs count sample URL is api/logs/count?statusCode=201&startDate=2022-09-17&endDate=2022-09-17&serviceNames[]=order-service
    
```