# Google Fast Indexing PHP library
*Библиотека для быстрой индексации сайта (страниц сайта) в поисковой выдаче Google. 
Создано на основе библиотеки [google/apiclient](https://github.com/googleapis/google-api-php-client)*.
*Подробное руководство от получения API-ключа Google до отправки url-адресов на индексацию доступно по [ссылке](https://webseed.ru/sozdanie-pdf-v-laravel-10-novyj-paket-laravel-pdf-ot-spatie/)*

*Library for quick indexing of a site (site pages) in Google search results.
Based on the library [google/apiclient](https://github.com/googleapis/google-api-php-client)*.
*A detailed guide from obtaining a Google API key to submitting URLs for indexing is available at [link](https://webseed.ru/sozdanie-pdf-v-laravel-10-novyj-paket-laravel-pdf-ot-spatie/)*

## Requirements (Требования)
*  php: ^7.4 || ^8.0
## Installation (Установка)
```shell
composer require dllpl/google-fast-indexing
```
## Usage example (Вызов)
```php
use Dllpl\Google\FastIndexing;

// (en) path to the .json file of the private key received in the Google account
// (ru) путь до .json файла приватного ключа, полученного в ЛК Google
$fastIndexing = new FastIndexing('service_account.json'); 

// (en) path to the .txt file containing URLs to be sent for indexing
// (ru) путь до .txt файла, содержащего url-адреса для отправки на индексацию
$result = $fastIndexing->send('url.txt'); 

var_dump($result);
```
## P.S.
*Recently, Google has been very poorly and very slowly indexing website pages on its own,
Based on this, there was a desire to implement this package for our own needs and for the needs of the entire GitHub community.
Thank you for your stars! I invite you to take part in the development of this package.*

*В последнее время Google очень плохо и совсем неспешно самостоятельно индексирует страницы сайта, 
исходя из этого возникло желание реализовать данный пакет для собственных нужд и для нужд всего GitHub-комьюнити. 
Спасибо за ваши звезды! Приглашаю принять участие в развитие данного пакета.*



