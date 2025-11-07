# TransactionAPI

REST API для управления балансом пользователей. Реализованы операции пополнения, снятия, перевода средств и проверки
баланса.
Проект работает в контейнерах через **Laravel Sail** с базой данных **PostgreSQL**.

---

## Технологии

* **PHP 8.4**, **Laravel 11**
* **PostgreSQL 18**
* **Laravel Sail (Docker)**
* **PHPUnit** для тестирования

---

## Установка и запуск

### 1. Клонирование репозитория

```bash
git clone https://github.com/maxim-tszyu/TransactionAPI
cd TransactionAPI
```

### 2. Установка зависимостей

```bash
composer install
```

### 3. Создание `.env`

```bash
cp .env.example .env
```

### 4. Генерация ключа приложения

```bash
./vendor/bin/sail artisan key:generate
```

### 5. Запуск контейнеров

```bash
./vendor/bin/sail up -d
```

### 6. Миграции и сиды

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

Автоматически создаются три пользователя:

- `Example user 1`
- `Example user 2`
- `Example user 3`

---

## API эндпоинты

Вы можете посмотреть Postman коллекцию в файле [TransferAPI collection.postman_collection.json](TransferAPI%20collection.postman_collection.json)

### POST `/api/deposit`

**Назначение:** пополнение баланса пользователя

**Параметры:**

* `user_id` - ID пользователя
* `amount` - сумма пополнения
* `comment` (optional) - комментарий к переводу

**Пример запроса:**

```json
{
  "user_id": 1,
  "amount": 500,
  "comment": "пример"
}
```

---

### POST `/api/withdraw`

**Назначение:** снятие средств с баланса пользователя

**Параметры:**

* `user_id` - ID пользователя
* `amount` - сумма снятия

**Пример запроса:**

```json
{
  "user_id": 1,
  "amount": 200
}
```

---

### POST `/api/transfer`

**Назначение:** перевод средств между пользователями

**Параметры:**

* `from_user_id` - ID отправителя
* `to_user_id` - ID получателя
* `amount` - сумма перевода
* `comment` (optional) - комментарий к переводу

**Пример запроса:**

```json
{
  "from_user_id": 1,
  "to_user_id": 2,
  "amount": 150
}
```

---

### GET `/api/balance/{user_id}`

**Назначение:** получение текущего баланса пользователя

**Параметры:**

* `user_id` - ID пользователя (в URL)

**Пример запроса:**

```
/api/balance/1
```

**Пример ответа:**

```json
{
  "user_id": 1,
  "balance": "150.00"
}
```

## Тестирование

### Запуск тестов

```bash
./vendor/bin/sail artisan test
```

### Пример вывода

```
PASS  Tests\Feature\BalanceApiTest
✓ deposit successful
✓ withdraw not enough balance
✓ transfer between users
✓ balance returns correct value
```

## Лицензия

MIT [LICENSE](LICENSE). 