# Vacation Planner

A vacation planner allowing employees to request vacations, as well supervisors to approve their employees vacation requests.

## Prerequisites

For the project to work you will need to have docker and docker-compose installed in your computer

## Setup

Clone the project and navigate to it's folder.

Run the following commands:

```composer install```

```cd frontend && npm install```

```cd ..``` 

```cp .env.example .env```

```cp frontend/.env.example frontend/.env```

The only thing you have to change is the SMTP settings in the .env file of the backend application located in the root of the project. To run locally create an account in [https://mailtrap.io](https://mailtrap.io) and set the username and password of you inbox in the .env file.

```docker-compose up``` (Wait for everything to boot)

In another terminal run the following commands:

```npm run migrate```

```npm run seed```

You are ready to go.

## How it works

The application is served through docker. You can access the API at [http://localhost:8081](localhost:8081) and the frontend at [http://localhost:8080](localhost:8080). The whole process starts from public/index.php and then moves to the original application.

## Default data

By default the seeders will create 4 users, 2 employees and 2 supervisors so you can test the application. Feel free to remove them.

#### User 1

Email: employee1@test.com

Password: demopass1

Supervisor: supervisor1@test.com

#### User 2

Email: employee2@test.com

Password: demopass2

Supervisor: supervisor2@test.com

#### User 3

Email: supervisor1@test.com

Password: demopass3

#### User 4

Email: supervisor2@test.com

Password: demopass4
