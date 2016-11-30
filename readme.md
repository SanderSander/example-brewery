# Initial Setup

Requirements `npm`, `composer`, `php`, `gulp`

```
git clone git@github.com:SanderSander/example-brewery.git
cd example-brewery
cp .env.example .env
composer install
npm install
gulp
touch database/database.sqlite
php artisan migrate
```

# Other

Add development dataset to database. 

`php artisan php artisan db:seed`

Run development web server

`php artisan serve`
