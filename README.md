# YAMAGUCHI API Demo Project

## Requirements
* [PHP ^8.1](https://www.php.net/downloads)
* [Composer](https://getcomposer.org/)

## Stack
* [Laravel 10](https://laravel.com/)

## 3rd parties
* [tymon/jwt-auth](https://packagist.org/packages/tymon/jwt-auth)
* [darkaonline/l5-swagger](https://packagist.org/packages/darkaonline/l5-swagger)

## Description
Демонстрационный проект API на Laravel 10 для YAMAGUCHI.

## Installation
1. Склонировать репозиторий на локальный компьютер
2. В корне проекта выполнить команду `php artisan demo:initialize`
3. Результат:
    * Создан файл окружения `.env`
    * Создан файл базы данных `database.sqlite`
    * Миграции проведены успешно
    * Локальный веб-сервер запущен

## API Documentation
После выполнения инициализации и запуска веб-сервера документация **Swagger** будет доступна по адресу: 
http://localhost:8000/api/documentation
