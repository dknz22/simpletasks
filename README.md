# SimpleTasks API

## Описание проекта
SimpleTasks — это RESTful API для управления задачами в компании. Система позволяет создавать, назначать и управлять задачами сотрудников, группировать их по статусам, а также фильтровать и сортировать данные.

## Используемые технологии
- **PHP 8.2+**
- **Laravel 12**
- **MariaDB**

## Установка и запуск
```bash
# Клонируем репозиторий
git clone https://github.com/dknz22/simpletasks.git
cd simpletasks

# Устанавливаем зависимости
composer install

# Создаем файл .env и настраиваем подключение к базе данных
cp .env.example .env

# Генерируем ключ приложения
php artisan key:generate

# Запускаем миграции и сидеры
php artisan migrate --seed

# Запускаем сервер
php artisan serve
```

# API Endpoints

## 1. Сотрудники (Employees)
| Метод | URL | Описание |
|--------|------------------|---------------------------|
| **GET** | `/api/employees` | Получить список сотрудников |
| **POST** | `/api/employees` | Создать нового сотрудника |
| **GET** | `/api/employees/{id}` | Получить информацию о сотруднике |
| **PUT** | `/api/employees/{id}` | Обновить данные сотрудника |
| **DELETE** | `/api/employees/{id}` | Удалить сотрудника |
| **POST** | `/api/employees/{id}/roles` | Обновить роли сотрудника |

### 1.1 Получить список сотрудников
**GET /api/employees**

#### Query параметры:
| Параметр      | Тип     | Описание                                           |
|--------------|--------|--------------------------------------------------|
| status       | string | Фильтр по статусу: `active` или `on_leave`      |
| sort_by      | string | Поле для сортировки: `id`, `name`, `status`      |
| sort_order   | string | Направление сортировки: `asc`, `desc`            |
| page         | int    | Номер страницы                                   |

#### Пример запроса:
```http
GET /api/employees?status=active&sort_by=name&sort_order=asc&page=1
```

#### Пример ответа:
```json
{
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "status": "active"
    }
  ],
  "current_page": 1,
  "total": 20
}
```

---

### 1.2 Создать сотрудника
**POST /api/employees**

#### JSON входные данные:
| Поле    | Тип     | Обязательное | Описание                                      |
|---------|--------|--------------|-----------------------------------------------|
| name    | string | Да           | Имя сотрудника                               |
| email   | string | Да           | Email (уникальный)                           |
| status  | string | Да           | Статус: `active` или `on_leave`              |

#### Пример запроса:
```http
POST /api/employees
Content-Type: application/json
```
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "status": "active"
}
```

#### Пример ответа:
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "status": "active"
}
```

---

### 1.3 Получить сотрудника по ID
**GET /api/employees/{id}**

#### Пример ответа:
```json
{
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "status": "active",
    "tasks": []
}
```

---

### 1.4 Обновить сотрудника
**PUT /api/employees/{id}**

#### JSON входные данные:
| Поле    | Тип     | Обязательное | Описание                                      |
|---------|--------|--------------|-----------------------------------------------|
| name    | string | Нет          | Новое имя сотрудника                         |
| email   | string | Нет          | Новый email (уникальный)                     |
| status  | string | Нет          | Новый статус: `active` или `on_leave`        |

#### Пример запроса:
```http
PUT /api/employees/1
Content-Type: application/json
```
```json
{
  "name": "Jane Doe",
  "status": "on_leave"
}
```

#### Пример ответа:
```json
{
  "id": 1,
  "name": "Jane Doe",
  "email": "john@example.com",
  "status": "on_leave"
}
```

---

### 1.5 Удалить сотрудника
**DELETE /api/employees/{id}**

#### Пример запроса:
```http
DELETE /api/employees/1
```

#### Пример ответа:
```json
{
  "message": "Employee deleted"
}
```

---

### 1.6 Обновить роли сотрудника
**POST  /api/employees/{id}/roles**

#### Пример запроса:
```json
{
    "role_ids": [1, 2]
}
```

#### Пример ответа:
```json
{
    "message": "Roles updated successfully"
}
```

---

## 2. Задачи (Tasks)
| Метод | URL | Описание |
|--------|------------------|---------------------------|
| **GET** | `/api/tasks` | Получить список задач |
| **POST** | `/api/tasks` | Создать новую задачу |
| **GET** | `/api/tasks/{id}` | Получить информацию о задаче |
| **PUT** | `/api/tasks/{id}` | Обновить данные задачи |
| **DELETE** | `/api/tasks/{id}` | Удалить задачу |
| **POST** | `/api/tasks/{id}/assign` | Назначить сотрудников на задачу |
| **GET** | `/api/tasks/grouped` | Получить задачи, сгруппированные по статусу |

### 2.1 Получить список задач
**GET /api/tasks**

#### Query параметры:
| Параметр      | Тип     | Описание                                           |
|--------------|--------|--------------------------------------------------|
| status       | string | Фильтр по статусу: `to_do`, `in_progress`, `done` |
| created_from | date   | Дата создания от (YYYY-MM-DD)                      |
| created_to   | date   | Дата создания до (YYYY-MM-DD)                      |
| sort_by      | string | Поле для сортировки: `id`, `title`, `status`      |
| sort_order   | string | Направление сортировки: `asc`, `desc`             |
| page         | int    | Номер страницы                                   |

#### Пример запроса:
```http
GET /api/tasks?status=to_do&sort_by=created_at&sort_order=desc
```

#### Пример ответа:
```json
{
  "data": [
    {
      "id": 1,
      "title": "Fix Bug #123",
      "description": "Fix the login bug",
      "status": "to_do"
    }
  ],
  "current_page": 1,
  "total": 50
}
```

---

### 2.2 Создать задачу
**POST /api/tasks**

#### JSON входные данные:
| Поле        | Тип     | Обязательное | Описание                         |
|------------|--------|--------------|----------------------------------|
| title      | string | Да           | Название задачи                  |
| description | string | Нет          | Описание задачи                   |
| status     | string | Да           | Статус: `to_do`, `in_progress`, `done` |

#### Пример запроса:
```http
POST /api/tasks
Content-Type: application/json
```
```json
{
  "title": "Fix Bug #123",
  "description": "Fix the login bug",
  "status": "to_do"
}
```

#### Пример ответа:
```json
{
  "id": 1,
  "title": "Fix Bug #123",
  "description": "Fix the login bug",
  "status": "to_do"
}
```
*Примечание: Если задача никому не будет назначена в течение 2 минут, она будет автоматически удалена.*

---

### 2.3 Получить задачу по ID
**GET /api/tasks/{id}**

#### Пример ответа:
```json
{
    "id": 1,
    "title": "Fix Bug",
    "description": "Fix critical bug in API",
    "status": "to_do",
    "employees": []
}
```

---

### 2.4 Обновить задачу
**PUT /api/tasks/{id}**

#### Пример запроса:
```http
PUT /api/tasks/1
Content-Type: application/json
```
```json
{
    "status": "in_progress"
}
```

#### Пример ответа:
```json
{
    "id": 1,
    "title": "Fix Bug",
    "description": "Fix critical bug in API",
    "status": "in_progress"
}
```
*Примечание: Если статус задачи будет изменен на "in_progress" или "done", все назначенные сотрудники получат уведомление.*

---

### 2.5 Удалить задачу
**DELETE /api/tasks/{id}**

#### Пример запроса:
```http
DELETE /api/tasks/1
```

#### Пример ответа:
```json
{
    "message": "Task deleted"
}
```

---

### 2.6 Назначить задачу сотруднику
**POST /api/tasks/{id}/assign**

#### Пример запроса:
```json
{
    "employee_ids": [1, 2]
}
```

#### Пример ответа:
```json
{
    "message": "Task assigned successfully"
}
```
*Примечание: Если статус сотрудника (on_leave), он не может быть назначен.*

---

### 2.7 Получить задачи сгруппированные по статусу
**GET /api/tasks/grouped**

#### Пример ответа:
```json
{
    "to_do": [
        {
            "id": 1,
            "title": "Fix Bug",
            "description": "Fix critical bug in API",
            "employees": [
                {
                    "id": 1,
                    "name": "John Doe",
                    "email": "john@example.com"
                }
            ]
        }
    ],
    "in_progress": [],
    "done": []
}
```

---

## Обработка ошибок
API возвращает стандартные HTTP-коды:
- `200 OK` — успешный запрос
- `201 Created` — успешное создание ресурса
- `400 Bad Request` — неверные параметры запроса
- `404 Not Found` — ресурс не найден
- `422 Unprocessable Entity` — ошибка валидации
- `429 Too Many Requests` — слишком много запросов
- `500 Internal Server Error` — внутренняя ошибка сервера

---

## Очереди и автоматические процессы
- Если задача не назначена ни одному сотруднику в течение **2 минут**, она **автоматически удаляется** (`DeleteUnassignedTask`).
- Если статус задачи изменяется на `in_progress` или `done`, сотрудники получают уведомление (`TaskStatusUpdated`).