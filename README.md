# 🧠 Task Manager API (Laravel Backend Only)

Task Manager — это backend-приложение, разработанное на Laravel 12 + PHP 8.2, демонстрирующее архитектурный подход, работу с авторизацией, фильтрацией, ИИ-интеграцией и Docker-инфраструктурой.  
Создан в рамках обучения и как часть портфолио backend-разработчика.

---

## 🚀 Возможности

- 🔐 Аутентификация с помощью Laravel Sanctum
- 📋 CRUD задач, каждая задача содержит:
  - название
  - описание
  - дедлайн
  - приоритет (low, medium, high)
  - теги (многие-ко-многим)
- 🔎 Гибкая фильтрация и сортировка по:
  - названию, приоритету, тегам, дедлайну
  - фильтрация работает по принципу `AND`
- 🧠 Интеграция с ИИ (OpenRouter API):
  - генерация тегов по названию и описанию задачи
- 🎨 Swagger-документация (`/api/documentation`)
- 🐳 Docker + Makefile для быстрого запуска
- 🧱 Чистая архитектура: контроллеры, сервисы, реквесты, ресурсы

---

## 🔧 Установка и запуск (Docker)

> Требуется: Docker, Make

```bash
git clone https://github.com/JluMoH-code/TaskManager-back.git
cd task-manager
make up
make command c="composer install"
make command c="cp .env.example .env"
make artisan c="key:generate"
make artisan c="migrate --seed"
```

Make-файл выполнит:
- запуск контейнеров (php, postgres, nginx)
- установку зависимостей
- копирование `.env` и генерацию APP_KEY
- запуск миграций и сидеров

Прописать в /etc/hosts
- 127.0.0.1 api.task-manager

Проект будет доступен по адресу:  
📡 http://api.task-manager

---

## 🧠 Интеграция с ИИ

Через OpenRouter API реализована генерация релевантных тегов на основе:
- названия задачи
- описания (опционально)

⚙️ Пример промпта:
```txt
Generate a list of tags in the same language as the title...
Return the result as a strictly valid JSON array of strings...
```

Ответ: `["покупки", "дом", "вечер"]`  
Результат парсится, валидируется, и только по подтверждению пользователя теги записываются в БД (через `firstOrCreate`).

---

## 🔍 Фильтрация задач

Фильтрация объединяет сразу несколько условий:

- `title` — по вхождению (ILIKE)
- `priority[]` — множественный выбор
- `tags[]` — каждый тег должен присутствовать
- `deadline_from` / `deadline_to` — интервал
- `active_only` — только активные задачи

🔃 Сортировка: по `title`, `deadline`, `priority`  
💡 Для `priority` реализован кастомный порядок через `CASE WHEN`

---

## 📦 Сидеры и фабрики

Для демонстрации данных:
- 5 пользователей
- 10 задач на каждого
- от 0 до 5 тегов на задачу
- Используется `faker` + сидеры

---

## 📘 Swagger-документация

Каждый эндпоинт описан вручную с помощью атрибутов `#[OA\...]`.

🗂 Поддерживаются:
- параметры запроса
- схемы моделей
- перечисления
- ответы
- примеры

Доступ: `/api/documentation`

---

## 💡 Технологии

- Laravel 12, PHP 8.2
- PostgreSQL 17.5
- Laravel Sanctum
- OpenAPI (Swagger)
- Docker, Make
- ИИ-интеграция (OpenRouter API)
- Чистая архитектура: MVC + сервисы + DI

---

## 📌 В планах

- ✅ Покрытие API-тестами (PHPUnit)
- ✅ Очередь на генерацию тегов
- ✅ CI/CD (GitHub Actions)

---