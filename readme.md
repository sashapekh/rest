# Простий rest (php 8)

### Щоб підняти сервер, виконуємо наступну команду
`cd public && php -S localhost:8080`

### Реалізовані наступний функціонал
> **Нотатка**
> 
> писав щоб application був трохи схожий на laravel

- регістрація роутів (`src/routes/web.php`)
- обробка контроллерів (`src/controllers/*`)
- Логування в application
- Обробка мідлварів

> Використовував psr інтерфейси а саме

- [psr-3](https://www.php-fig.org/psr/psr-3/)
- [psr-7](https://www.php-fig.org/psr/psr-7/)
- [psr-15](https://www.php-fig.org/psr/psr-15/)


### Реалізовано наступні роути

- `localhost:8080/` (GET) - вивід просто тексту на головній сторінці
-  `localhost:8080/users` (GET) - вивід всіх користувачів
- `localhost:8080/users/{id}` (GET) - вивід конктетного користувача (тут додав AuthUserMiddleware шоб перевірити доступ до роуту токену )

> Приклад запиту на /users/{id}
```
curl --location 'http://localhost:8080/users/1' \
--header 'Authorization: Token dXNlcjFAZ21haS5jb206JDJ5JDEwJHlad1dNdC96MVYxaFcxYmQ4VHlpZGVCcHZzUTQweGF5TUVwYzNSbDMvL0NhaXZ0cGxEQXdX'
```
Токен в собі містить base_64 зашифровану строку яка містить `email:passwordlHash` (думаю що це не секьюрно , але треба було по чомусь перевіряти)

- `localhost:8080/login` (POST) - отриманя токена

> Приклад запиту (User має зашифрований пароль 'secret' для всіх користувачів)

```
curl --location 'http://localhost:8080/login' \
--header 'Content-Type: application/json' \
--data-raw '{
    "email": "user1@gmai.com",
    "password": "secret"
}'
```

### Логування

> Лог дані записуються у файл logs/logger.log
>
#### Наступні методи доступні

- `core/Log/Log::info(\Stringable|string $message, array $context = [])` - інформаційний лог
- `core/Log/Log::error(\Exception $exception)` - лог про помилку
