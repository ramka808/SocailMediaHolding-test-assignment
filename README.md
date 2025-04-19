# Support ticket system

## Run Locally

```bash
     cp .env.example .env
```
Edit env for your database

```bash
     php artisan key:generate
```

```bash
     php artisan migrate
```


```bash
      php artisan serve
```

## Добавить телефоны в бд GET http://127.0.0.1:8000/addPhones
## Вывести телефоны из бд GET http://127.0.0.1:8000/phones
