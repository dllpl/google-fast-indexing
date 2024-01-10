# Google Fast Indexing PHP library
*Библиотека для быстрой индексации сайта (страниц сайта) в поисковой выдаче Google*
## Требования
*  **php: ^7.4 || ^8.0**
## Установка
```shell
composer require dllpl/google-fast-indexing
```
## Вызов
```php
use Dllpl\Google\FastIndexing;

// путь до .json файла полученного в ЛК Google
$fast_indexing = new FastIndexing('service_account.json'); 

// путь до .txt файла содержащего url-адреса для отправки на индексацию
$result = $fast_indexing->send('url.txt'); 

// результат отправки по каждому адресу
echo $result;
```



