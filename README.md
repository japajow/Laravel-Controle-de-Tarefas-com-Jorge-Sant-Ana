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

## Verificando se o usuario esta logado dentro dos metodos Controller

Temos duas formas de verificar se o usuario esta logado ou nao.

```php

auth()->check();
```

segunda forma e usando a classe

```php

Auth::check();
```

podemos pegar os dados do usuario

```php
auth()->user()->id;
auth()->user()->name;
auth()->user()->email;

ou usando a classe

Auth::user()->id;
Auth::user()->name;
Auth::user()->email;

```
## criando um template de e-mail markdown maiables

php artisan make:mail <Nome da classe> <view associada a classe>
php artisan make:mail MensagemTesteMail --markdown emails.mensagem-teste

Vamos na rotas e incluir uma rota para testaer o email

```php

Route::get('/mensagem-teste', function(){
    return new MensagemTesteMail();
});
```

## Enviando emails

```php
 Mail::to('sitewnkcorporate@gmail.com')->send('new MensagemTesteMail()');
 return 'E-mail enviado com sucesso!';
```

enviando email pelo tinker

php artisan tinker
use App\Mail\MensagemTesteMail;
Mail::to('sitewnkcorporate@gmail.com')->send('new MensagemTesteMail()');









