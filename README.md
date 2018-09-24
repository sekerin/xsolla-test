# xsolla-test

Простая система ведения файлового хранилища.

# Установка

Клонируем проект

```git clone https://github.com/sekerin/xsolla-test.git```

Создаем переменные окружения для бэкэнда:

```cd backend/```

```ln -s .env.dist .env```

Создаем переменные окружения для docker:

```cd ../```

```ln -s .env.dist .env```

Устанавливаем зависимости

```composer install``` - для бэкэнда

```npm i``` - для фронтенда

Запускаем с помощью [docker-compose](https://docs.docker.com/compose/)

```docker-compose up```

Система будет доступна по адресу http://sf.local/. Хосты sf.local, sf-assets.local и symfony-stack.local необходимо добавить в файл hosts системы, с IP по которому доступен Docker.

http://sf.local - форнтенд приложения

http://sf-assets.local - [Webpack dev server](https://github.com/webpack/webpack-dev-server)

http://symfony-stack.local - бэкэнд

# API бэкэнда

## Формат ошибок:

```
{
  errors: [ string ]
}
```

## Получение списка файлов

```GET /files/```

### Ответ сервера

Массив метаданных файлов

```
{
items: [
    {
      name: string,
      path: string,
      size: int,
      mime: string
    },...
  ]
}
```

## Создание файла

```POST /files/```

### Параметры запроса

- file - файл, загружаемый пользователем

### Ответ сервера

Метаданные загруженного файла

```
{
item: {
    name: string,
    path: string,
    size: int,
    mime: string
  }
}
```

### Ошибки

- 409 - Файл уже существует

## Обновление файла

```POST /files/filename```, где filename - имя файла, который необходимо обновить

### Параметры запроса

- file - файл, загружаемый пользователем

### Ответ сервера

Метаданные обновленного файла

```
{
item: {
    name: string,
    path: string,
    size: int,
    mime: string
  }
}
```

### Ошибки

- 404 - файла не существует

## Удаление файла

```DELETE /file/filename```, где filename - имя файла, который необходимо удалить

### Ответ сервера

Имя удаленного файла

```
{
item: {
    name: string
  }
}
```

### Ошибки

- 404 - файла не существует
