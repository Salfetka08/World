# Как получилось запустить Swagger?

1) composer require darkaonline/l5-swagger # Устанавливает пакет L5-Swagger через Composer
2) php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"  # Публикует конфигурационные файлы пакета
3) php artisan config:clear # Очищает кэш конфигурации Laravel
4) php artisan cache:clear # Очищает весь кэш приложения
5) php artisan l5-swagger:generate # Генерирует документацию Swagger из аннотаций
6) php artisan serve # Запускает встроенный сервер Laravel
7) URL: http://127.0.0.1:8000/api/documentation # Выводит URL для доступа к документации
