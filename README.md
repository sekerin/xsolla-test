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

# Gzip сжатие ответов

Для сжатия настроен nginx, для всех запросов в заголовке есть `Accept-Encoding: gzip`:

```
  gzip on;
  gzip_types      text/plain application/xml application/json;
  gzip_proxied    any;
  gzip_min_length 1000;
  gunzip on;
```

# Поддержка gzip запросов

Для поддержки gzip запросов необходимо установить в nginx модули `nginx-extras` `lua-zlib` и реализовать декомпрессию на Lua.

# Оптимизация загрузки и отдачи файлов (в первую очередь, используемой памяти)

Для оптимизации используемой памяти при работе с файлами используются потоки

# Хранение файлов в сжатом виде

Система использует стримы, по этому реализация заключается в добавлении к загрузке сжимаюещго потокового фильтра `zlib.deflate` `stream_filter_append($stream, 'zlib.deflate', STREAM_FILTER_WRITE)`. При отдаче файла необходимо использовать фильтр `zlib.inflate` `stream_filter_append($file, 'zlib.inflate')`

# Разруливание конкурентного доступа к файлу

Мржно реализовать через блокировку потоков для перезаписи.

# Работа с оооочень большими файлами

Отдача файлов кусками

```
function readfile_chunked($filename, $retbytes = true)
    {
        $buffer = "";
        $cnt = 0;
        $handle = fopen($filename, "rb");
        if ($handle === false) {
            return false;
        }
        while (!feof($handle)) {
            $buffer = fread($handle, CHUNK_SIZE);
            echo $buffer;
            ob_flush();
            flush();
            if ($retbytes) {
                $cnt += strlen($buffer);
            }
        }
        $status = fclose($handle);
        if ($retbytes & $status) {
            return $cnt;
        }
        return $status;
    }
```
# Лимиты на размер загружаемых файлов

Настройка для nginx `client_max_body_size max_size;`, где max_size - максималный  размер файла.

Настройки для php `post_max_size = max_size` и `upload_max_filesize = max_size`, где max_size - максималный  размер файла.

# HTTPS

Лучше всего установить ssl для проксирующего nginx

```
...
listen               443 ssl;
...
ssl_certificate      certificate_bundled.crt;
ssl_certificate_key  private.key;
...
```

# HTTP заголовки в ответах, для безопасного вызова API со сторонней веб-страницы в браузере

Использование CORS протокола. Реализовано через отдельный action, принимающий OPTIONS запросы в контроллере. Разрешенный хост указывается через переменную окружения CORS_ACCEPT.

Можно (будет более правильно) исползовать библиотеку NelmioCorsBundle.

# Механизм авторизации в апи

Для аутентификации в апи можно использовать пакет `symfony/security-bundle`.

Создать реализауию `UserInterface`, определения пользователя. 

Созрать реализацю `UserProviderInterface` для определения места хранения пользователей.

Созадть реализацию интерфейса `AbstractGuardAuthenticator` - непосредственно процесс аутентификации

Настроить security-bundle, добавив реализацию реализацию интерфейса `AbstractGuardAuthenticator` в секцию `guard/          authenticators`

В каждом запросе передавать заголовок X-AUTH-TOKEN, по которому аутентифицировать пользователя, в случае ошибки возвращать:

```
{
  authentificate: false,
  errors: []
}
```

В случае успеха:

```
{
  authentificate: true,
  token: string
}
```

В случае ошибки фронтэнд должен удалить старый токен и показать пользователю форму аутентификации. В случае успеха - записать токен и передавать его во всех запросах.

# Квоты с местом и лимит для пользователя

Настройки пользователя лучше хранить в том же месте, где хранится данныве пользователя. При хранении в базе данных лучше создать view или хранимую процедуру для получения занятого места.

При использовании Unix аутентификации (PAM) можно использовать квоты файловой системы при монтировании файловой системы.
Создаем пользователя - создаем ему директорию - монтируем ему ограниченную ФС в эту директоирию. При таком подходе дополнительной обработки файловых квот не требуется.
