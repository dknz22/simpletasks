### **Docker не тестировался**

### Запуск проекта
1. Переименовать `.env.example` в `.env` и настроить переменные окружения.
2. Запустить проект:
```sh
docker-compose up -d --build
```
3. Примените миграции и seed-данные:
```sh
docker-compose exec app php artisan migrate --seed
```
*Приложение будет доступно по адресу: `http://localhost:8080`*