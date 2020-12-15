## About Application

Example application in Laravel 8.x. Application generates photo collage out of 4 random photos from https://picsum.photos 
or from photos provided by user.

## Installation

```
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan passport:install

npm install
npm run dev
```

## Usage

This application provides API for creating and managing (CRUD) your photo collages.

You can create account on http://photocollage.test/register path, initial account is also created by seeder:
```
user@example.org
!QAZ2wsx
```

API uses Laravel Passport for authentication, so you also need Client ID and Client Secret from database to authenticate (they are generated during installation process), for example:
```
$response = Http::asForm()->post('http://photocollage.test/oauth/token', [
    'grant_type' => 'password',
    'client_id' => 'client-id',
    'client_secret' => 'client-secret',
    'username' => 'user@example.org',
    'password' => '!QAZ2wsx',
    'scope' => '',
]);
```

Use returned access_token as Bearer token to authenticate to API.

You can also login to dashboard (http://photocollage.test/login path) to access your generated photo collages.
