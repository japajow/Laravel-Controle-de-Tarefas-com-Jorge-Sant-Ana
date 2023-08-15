## Creando um projeto com Laravel
-- Jorge Sant Ana --
-- Ver-Laravel: 8.5.9 --
-- Data: 15/08/2023 --
-- Autor: Bruno Hamawaki --

# Laravel Controle de Tarefas com Laravel --

# Instalando  o Laravel UI
composer require laravel/ui:^3.2

saber aonde o php.ini esta alocada na maquina
php --ini

php artisan list

## Ententendo o pacote UI
 -- iniciando a autenticacao WEB nativa do laravel

 php artisan ui bootstrap --auth // react ou Vue

 npm install && npm run dev

npm install resolve-url-loader@^5.0.0 --save-dev --legacy-peer-deps


## Configurando o banco de dados

## Criando model e controller para a tarefa

php artisan make:controller --resource TarefaController --model=Tarefa

## implementando a middleware auth

Criando a rotas

```php
Route::resource('tarefa','App\Http\Controllers\TarefaController');
```

Incluindo a middleware no construtor tarefaController
```php
public function __construct()
    {
        $this->middleware('auth');
    }
```







