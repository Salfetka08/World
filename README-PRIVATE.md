# Как получилось запустить Swagger?

1) composer require darkaonline/l5-swagger
2) php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
3) php artisan config:clear
4) php artisan cache:clear
5) php artisan l5-swagger:generate
6) php artisan serve
7) URL для тесты: http://127.0.0.1:8000/api/documentation


