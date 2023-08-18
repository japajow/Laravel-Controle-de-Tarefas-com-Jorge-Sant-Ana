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

## Publicando e customizando o template do e-mail

php artisan vendor:publish
14


## Configurando o envio de e-mails (Reset Password)
app/Models/User.php
criamos uma funcao que intercepta o padrao de recuperacao de senha

```php
 public function sendPasswordResetNotification($token){
        return 'chegamos ate aqui';
    }

```

criamos um notification para manipular o template dele

php artisan make:notification RedefinirSenhaNotification

incluimos o notify() instaciamos a classe Notifications criada acima
e passamos o token no parametro
```php

public function sendPasswordResetNotification($token){
         $this->notify(new RedefinirSenhaNotification($token));
    }
```

recuperamos o token na classe passando no metodo construtor
criamos a variavel token

```php

public $token;

  public function __construct($token)
    {
        $this->token = $token;
    }

```
Copiamos as instrucoes

```php

return (new MailMessage)
            ->subject(Lang::get('Reset Password Notification'))
            ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
            ->action(Lang::get('Reset Password'), $url)
            ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(Lang::get('If you did not request a password reset, no further action is required.'));

```

c colamos no  nosso RedefinirSenhaNotification

trduzimos a mensagem
```php
       $url = 'ttp://localhost:8000/password/reset/'.$this->token;
        $minutos = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

        return (new MailMessage)
            ->subject(Lang::get('Atualização de senha'))
            ->line(Lang::get('Esqueceu a senha? Sem problemas, vamos resolver isso!!!'))
            ->action(Lang::get('Clique aqui para modificar a senha'), $url)
            ->line('Esse link vai expirar em : '.$minutos .' minutos.')
            ->line(Lang::get('Caso voce nao tenha solicitado a alteração de  senha, entre em contato.'));
    }


```

vamos passar o email no parametro para pegar o email
User.php
```php
 public function sendPasswordResetNotification($token){
         $this->notify(new RedefinirSenhaNotification($token),$this->email);
    }

```
Criamos uma variavel
e passamos no metodo construtor
RedefinirSenhaNotification.php
```php
public $email;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token,$email)
    {
        $this->token = $token;
        $this->email = $email;
    }

```
RedefinirSenhaNotification.php
Vamos passar na mensagem o email recuperado
```php
     $url = "http://localhost:8000/password/reset/{$this->token}?email={$this->email}";

```
Adicionando o nome no parametro

```php

 public function sendPasswordResetNotification($token){

         $this->notify(new RedefinirSenhaNotification($token,$this->email,$this->name));
    }

```

passando no construtor

```php
    public $token;
    public $email;
    public $name;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token,$email,$name)
    {
        $this->token = $token;
        $this->email = $email;
        $this->name = $name;
    }

```

incluindo a saudacao

```php
  ->greeting("Ola {$this->name}")

```

saudacao final modificando

```php
->salutation('Ate breve!')

```

vendor/laravel/framework/src/illuminate/Notifications/resources/views/email.blade.php
Modificando o footer do email padrao
```php
{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang( "Caso você tenha problemas no botão acima, copie e cole a url abaixo.",
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
@endslot
@endisset
@endcomponent

```

## Verificacao de email MustVerifyEmail
User.php MustVerifyEmail
implementamos na clsse
```php
class User extends Authenticatable implements MustVerifyEmail

```

em seguida no diretorio de routes/web.php

adicionamos

```php

Auth::routes(['verify' => true]);

```

Adicionando o middleware para somente ter acesso ao verificar a cont pelo email

```php
->middleware('verified')

```

## Customizando a view de verificacao

resources/view/auth/verify.blade.php

```php


```

## Cadastrando novas tarefas

Criamos a view e chamamos no controler TarefaController.php
create()

```php

 public function create()
    {
        return view('tarefa.create');
    }
```

criamos  resource/views/tarefa/create.blade.php
copiamos o conteudo Home.balde.php
```html
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


```

no card body colamos o codigo

```html

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Adicionar Tarefa</div>

                    <div class="card-body">
                        <form method="post" action="{{ route('tarefa.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="tarefa" class="form-label">Tarefa</label>
                                <input type="text" class="form-control" name="tarefa">
                            </div>
                            <div class="mb-3">
                                <label for="data" class="form-label">Data limite conclusao</label>
                                <input type="date" class="form-control" name="data_limite_conclusao">
                            </div>
                            <button type="submit" class="btn btn-primary">Cadastrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

```

vamos no controller e no metodo store fazer um teste

```php
 public function store(Request $request)
    {
        dd($request->all());
    }

```

implemtentando um tabela

php artisan make:migration create_tarefas_table

```php

 public function up()
    {
        Schema::create('tarefas', function (Blueprint $table) {
            $table->id();
            $table->string('tarefas',200);
            $table->date('data_limite_conclusao');
            $table->timestamps();
        });
    }
```

vamos executar a migrate
php artisan migrate

vamos armazenar os dados no TarefaCOntroller
     vamos chamar a classe Tarefa::create($request->all());
```php

 $rules = [
            'tarefa' => 'required|min:3|max:20',
        ];

        $params = [
            'required' => 'Campo :attribute e obrigatorio',
            'tarefa.min' => 'minimo 3 caracteres',
            'tarefa.max' => 'maximo 20 caracteres'
        ];

        $request->validate($rules,$params);

        Tarefa::create($request->all());
```

Vamos no Models e incluir as colunas para ser insertada no banco de dados

```php
protected $fillable = ['tarefa','data_limite_conclusao'];

```

passamos a criacao para uma variavel

```php
$tarefa =  Tarefa::create($request->all());

```

redirecionamos a tarefa para o metodo show com o id

```php

 return redirect()->route('tarefa.show',['tarefa'=>$tarefa->id]);
```

e no metodo show pegamos o atributo da tarefa que veio pelo store

## Enviando um e-mail de cadastro de nova tarefa e exibindo os dados da tarefa

criando o classe do Mail

php artisan make:mail NovaTarefaMail --markdown emails.nova-tarefa


no TarefaController vamos no metodo store e incluir a classe Mail::to

```php
use App\Mail\NovaTarefaMail; // importamos
    // pegando o email do usuario atual
  $destinatario = auth()->user()->email;
  Mail::to($destinatario)->send(new NovaTarefaMail()); // incluimos no store

```

passamos os dados da $tarefa na NovaTarefaMail($tarefa)

```php
  Mail::to($destinatario)->send(new NovaTarefaMail($tarefa));

```

Na classe NovaTarefaMail

```php

importamos a Tarefa
use App\Models\Tarefa;

incluimos no metodo construtor

   public function __construct(Tarefa $tarefa)
    {
        //
    }
```

la no markdown view do mail nova-tarefa vamos trabalhar com 3 parametros

```markdown

@component('mail::message')
# {{ $tarefa }}

Data limite de conclusao: {{ $data_limite_conclusao }}

@component('mail::button', ['url' => $url])
Clique aqui para ver a tarefa
@endcomponent

Att,<br>
{{ config('app.name') }}
@endcomponent

```

vamos crias as 3 variaveis  $tarefa,$url,$data_limite_conclusao
NovaTarefaMail
```php

criamos as variaveis

 public $tarefa;
    public $data_limite_conclusao;
    public $url;

    pegamos os valores no metodo construtor

      public function __construct(Tarefa $tarefa)
    {
        $this->tarefa = $tarefa->tarefa;
        // $this->data_limite_conclusao = $tarefa->data_limite_conclusao;
        // passando a data para dia/mes/ano
        $this->data_limite_conclusao = date('d/m/Y', strtotime($tarefa->data_limite_conclusao));

        $this->url = "http://localhost:8000/tarefa/{$tarefa->id}";
    }
```

adicionamos o sbject no markdown view

```php

  /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.nova-tarefa')->subject('Nova Tarefa criada');
    }
```

no TarefaCOntroller vamos no metodo show e retornar a view

```php
 public function show(Tarefa $tarefa)
    {
        return view('tarefa.show',['tarefa' =>$tarefa]);
    }

```

criando a view tarefa show

views/tarefas/show.blade.php

```html

//copiamos do create.blade.php e modificamos

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $tarefa->tarefa }}</div>
                    <div class="card-body">
                        <fieldset disabled="disabled">
                            <div class="mb-3">
                                <label for="data" class="form-label">Data limite conclusao</label>
                                <input type="date" class="form-control" value="{{ $tarefa->data_limite_conclusao }}">
                            </div>
                        </fieldset>
                        <a href="{{ url()->previous()  }}" class="btn btn-secondary">Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




```
voltando para a pagina anterios

{{ url()->previous()  }}

##

