# bnovo

## Первый запуск
1. `git clone https://github.com/awpm9so/bnovo.git`
2. `cd bnovo`
3. `cp .env.example .env`
4. `Заполнить своими значениями переменные окружения: DB_DATABASE, DB_USERNAME, DB_PASSWORD, PGADMIN_DEFAULT_EMAIL, PGADMIN_DEFAULT_PASSWORD (две последние для pgAdmin)`
5. `docker compose up -d --build`
6. `docker compose exec php bash`
7. `composer setup`

## Все последующие запуски
1. `docker compose up -d`

#### Тех. задание:
Написать микросервис работы с гостями используя язык программирования на выбор PHP или Go, можно пользоваться любыми opensource пакетами, также возможно реализовать с использованием фреймворков или без них. БД также любая на выбор, использующая SQL в качестве языка запросов. 

Микросервис реализует API для CRUD операций над гостем. То есть принимает данные для создания, изменения, получения, удаления записей гостей хранящихся в выбранной базе данных.

Сущность "Гость" Имя, фамилия и телефон – обязательные поля. А поля телефон и email уникальны. В итоге у гостя должны быть следующие атрибуты: идентификатор, имя, фамилия, email, телефон, страна. Если страна не указана то доставать страну из номера телефона +7 - Россия и т.д. 

Правила валидации нужно придумать и реализовать самостоятельно. Микросервис должен запускаться в Docker. 

Доп. обязательное условие для уровня Middle (по желанию для Junior): “В ответах сервера должны присутствовать два заголовка X-Debug-Time и X-Debug-Memory, которые указывают сколько миллисекунд выполнялся запрос и сколько Кб памяти потребовалось соответственно.”


#### Комментарии к решению:
1. Для реализации использован Laravel
2. Вместо сущности Клиент используется сущность Пользователь (из-за того, что стандартно в laravel создается таблица users. Сути это не меняет)
3. Телефон принимается в формате "{телефонный код страны} {номер}". В тех. задании не регламентирован формат телефона и для удобства определения страны решено сразу принимать телефонный код страны отдельно (разделитель - пробел) от самого номера.
4. Для определения страны создается новая таблица в БД - `Country`, которая содержит столбцы `name` (название страны) и `phone_code` (телефоный код страны). Таблица заполняется из xlsx файла, находящегося по пути /storage/app/countries.xlsx
5. Поле `country` при создании и редактировании пользователя не ссылается на таблицу Country. То есть для пользователя можно указать любую страну, даже несуществующую
6. Нет аутентификации и авторизации, то есть все действия с api может делать кто угодно (согласно ТЗ)
7. Добавлены заголовки для ответов сервера `X-Debug-Time` и `X-Debug-Memory`


### API

**Создание пользователя**: (POST) `http://localhost:81/api/user` 

    'name' => 'required|string|max:30'
    'last_name' => 'required|string|max:30'
    'email' => 'required|string|email|max:255|unique:users'
    'phone' => 'required|regex:/^[0-9]+ [0-9]+$/|string|max:25|unique:users'
    'country' => 'string|max:60'

Пример валидных данных:
```json
{
    "name": "Иван",
    "last_name": "Иванов",
    "email": "ivan@ivan.com",
    "phone": "44 5654796"
}
```
Ответ

`Статус: 201`
```json
{
    "data": {
        "id": 1,
        "name": "Иван",
        "last_name": "Иванов",
        "email": "ivan@ivan.com",
        "phone": "44 5654796",
        "country": "Великобритания",
        "created_at": "2024-09-22T20:21:28.000000Z",
        "updated_at": "2024-09-22T20:21:28.000000Z"
    }
}
```

**Получение пользователя**: (GET) `http://localhost:81/api/user/{id}`

    'id' => '[0-9]+'
Ответ

`Статус: 200`
```json
{
    "data": {
        "id": 1,
        "name": "Иван",
        "last_name": "Иванов",
        "email": "ivan@ivan.com",
        "phone": "44 5654796",
        "country": "Великобритания",
        "created_at": "2024-09-22T20:21:28.000000Z",
        "updated_at": "2024-09-22T20:21:28.000000Z"
    }
}
```

**Обновление пользователя**: (PUT) `http://localhost:81/api/user/{id}`
    
    'id' => '[0-9]+'
    'name' => 'string|max:30',
    'last_name' => 'string|max:30',
    'email' => 'string|email|max:255|unique:users',
    'phone' => 'regex:/^[0-9]+ [0-9]+$/|string|max:25|unique:users',
    'country' => 'string|max:60',

Ответ

`Статус: 200`
```json
{
    "data": {
        "id": 1,
        "name": "Максим",
        "last_name": "Максимов",
        "email": "maksim@maksim.com",
        "phone": "44 5654796",
        "country": "Дания",
        "created_at": "2024-09-22T20:21:28.000000Z",
        "updated_at": "2024-09-22T21:21:28.000000Z"
    }
}
```

**Удаление пользователя**: (DELETE) `http://localhost:81/api/user/{id}`

    'id' => '[0-9]+'

Ответ

`Статус: 200`
```json
{
    "message": "Пользователь успешно удален."
}
```

При передаче несуществующего или невалидного `id` ответ будет таким:

`Статус: 404`
```json
{
    "message": "Запись не найдена."
}
```
