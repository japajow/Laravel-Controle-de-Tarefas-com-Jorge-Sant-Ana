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

## Associando o usuario a tarefa

php artisan make:migration alter_table_tarefas_relacionamentos_users

migrations/alter_table_tarefas_relacionamentos_users

```php

 public function up()
    {
        Schema::table('tarefas', function(Blueprint $table){
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
```

```php

 public function down()
    {
        Schema::table('tarefas', function(Blueprint $table){
            $table->dropForeign('tarefas_user_id_foreign');
            $table->dropColumn('users_id');
        });
    }
```

apos ter configurado a migration vamos executar

php artisan migrate

ajustando para cadastrar a coluna user_id TarefaController

```php
metodo store

// criamos um array $dados = $request->all();
// criamos um indice $dados['user_id'] = auth()->user()->id
 $dados = $request->all();
$dados['user_id'] = auth()->user()->id;
$tarefa = Tarefa::create($dados);

dd($dados);
array:4 [▼
  "_token" => "BQyqi1xnuD2JT08TFk3YRdBeHhNCup5vQ5tzMnKD"
  "tarefa" => "Tarefa 60"
  "data_limite_conclusao" => "2023-08-20"
  "user_id" => 5
]

passamos as colunas que queremos pegar
 $dados = $request->all(['tarefa','data_limite_conclusao']);
       $dados['user_id'] = auth()->user()->id;
       dd($dados);

resposta
array:3 [▼
  "tarefa" => "Tarefa 60"
  "data_limite_conclusao" => "2023-08-20"
  "user_id" => 5
]

```
Precisamos ir la no MOdels e passar a coluna user_id para ser registrado

```php
class Tarefa extends Model
{
    use HasFactory;

    protected $fillable = ['tarefa','data_limite_conclusao','user_id'];
}
```
## Listando as tarefas cadastradas

criando a index.blade.php

resources/views/tarefas/index.blade.php

```html

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Tarefas</div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                              <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Tarefa</th>
                                <th scope="col">Data Limite conclusao</th>
                                <th scope="col"></th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <th scope="row"></th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td></td>
                              </tr>

                            </tbody>
                          </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
```

valtamos no TarefaConroller no index pagamos os dados no BD

```php
   public function index()
    {
        $user_id = auth()->user()->id; // pegamos o id do usuario logado
        $tarefas = Tarefa::where('user_id', $user_id); // passamos o where pegando o usuario logado
        return view('tarefa.index',['tarefas'=>$tarefas]);// passamos as tarefas relacionadas a view
    }

```

passando na views as tarefas

```html

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Tarefas</div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                              <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Tarefa</th>
                                <th scope="col">Data Limite conclusao</th>
                                <th scope="col"></th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($tarefas as $tarefa )
                              <tr>
                                <th scope="row">{{ $tarefa->id}}</th>
                                <td>{{ $tarefa->tarefa}}</td>
                                <td>{{ date('d/m/Y', strtotime($tarefa->data_limite_conclusao))}}</td>
                                <td></td>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

```

## implemanetando a paginacao de registros de tarefas

TarefaController
modificamos de get para paginate
```php
 public function index()
    {
        $user_id = auth()->user()->id;
        $tarefas = Tarefa::where('user_id', $user_id)->paginate(1);
        return view('tarefa.index',['tarefas'=>$tarefas]);
    }

```

na view index mostramos o links

```html
 <span>{{ $tarefas->links() }}</span>
```

o css esta quebrado vamos criar do zero as configuracao da paginacao

```html

<nav aria-label="Page navigation example">
  <ul class="pagination">
    <li class="page-item"><a class="page-link" href="#">Previous</a></li>
    <li class="page-item"><a class="page-link" href="#">1</a></li>
    <li class="page-item"><a class="page-link" href="#">2</a></li>
    <li class="page-item"><a class="page-link" href="#">3</a></li>
    <li class="page-item"><a class="page-link" href="#">Next</a></li>
  </ul>
</nav>


configurando assim

<nav aria-label="Page navigation example">
<ul class="pagination">
    <li class="page-item"><a class="page-link" href="{{ $tarefas->previousPageUrl() }}">Voltar</a></li>
    @for ($i=1;$i<=$tarefas->lastPage(); $i++)
    <li class="page-item"><a class="page-link {{ ($tarefas->currentPage() == $i)? 'active' : ''}}" href="{{ $tarefas->url($i) }}">{{ $i }}</a></li>
    @endfor

    <li class="page-item"><a class="page-link" href="{{ $tarefas->nextPageUrl() }}">Proximo</a></li>
</ul>
</nav>
```

## Modificando a rota defualt Home para a Lista de Tarefas

app/Providers/RoutesServiceProvider

```php

De
  public const HOME = '/home';

Para
  public const HOME = '/tarefa';
```

## Atualizando registro de tarefas

incluindo o link de editar na view

views/tarefas/index

```html
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Atualizar Tarefa</div>

                    <div class="card-body">
                        <form method="post" action="{{ route('tarefa.update',['tarefa' => $tarefa->id]) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="tarefa" class="form-label">Tarefa</label>
                                <input type="text" class="form-control" name="tarefa" value="{{ $tarefa->tarefa}}">
                               @error('tarefa')
                                <span class='text-danger'> {{ $message }}</span>
                               @enderror
                            </div>
                            <div class="mb-3">
                                <label for="data" class="form-label">Data limite conclusao</label>
                                <input type="date" class="form-control" name="data_limite_conclusao" value="{{ $tarefa->data_limite_conclusao}}">
                            </div>
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


```

no metodo edit passamos a tarefa na view

```php

  public function edit(Tarefa $tarefa)
    {
        dd($tarefa);
        return view('tarefa.edit',['tarefa' => $tarefa]);
    }

```

No metod update inserimos os validates e o update
```php

  public function update(Request $request, Tarefa $tarefa)
    {
        $rules = [
            'tarefa' => 'required|min:3|max:60',
        ];

        $params = [
            'required' => 'Campo :attribute e obrigatorio',
            'tarefa.min' => 'minimo 3 caracteres',
            'tarefa.max' => 'maximo 60 caracteres'
        ];

        $request->validate($rules,$params);

        $tarefa->update($request->all());
        return redirect()->route('tarefa.show',['tarefa'=>$tarefa]);
    }
```

## Validando se a tarefa pertence ai usuario


criando um blade na views direto
acesso-negado.blade.php

```html

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Acesso Negado</div>

                <div class="card-body">
                   <h3>Desculpe voce nao tem acesso a esse recurso</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

```

vamos na TarefaController
pegamos o id do usuario atual e o id da tarefa e comparamos , verificamos se e dele a tarefa

```php
 public function edit(Tarefa $tarefa)
    {
        $user_id = auth()->user()->id;
        $tarefa_id = $tarefa->user_id;

        if($tarefa_id == $user_id){
            return view('tarefa.edit',['tarefa' => $tarefa]);
        }else{
            return view('acesso-negado');
        }
    }

```

colocamos a verificacao tambem no update

```php


        $user_id = auth()->user()->id;
        $tarefa_id = $tarefa->user_id;
        if($tarefa_id == $user_id){
            $tarefa->update($request->all());
            return redirect()->route('tarefa.show',['tarefa'=>$tarefa]);
        }else{
            return view('acesso-negado');
        }

```

## removendo registro da tarefa

ilcuindo no index um link excluir que remove a tarefa
implementamos o formulario
passando a tag id concatenada com id da tarefa
method passamos post, mas passamos @method('DELETE')
passamo o token @crsf
passamos a action={{route('tarefa.destroy',['tarefa'=>$tarefa['id'])]}}
```html
 <td><form id="form-{{$tarefa['id']}}" action="{{ route('tarefa.destroy',$tarefa['id'])}}" method="post">
    @csrf
    @method('DELETE')
</form>
    <a href="#" onclick="document.getElementById('form-{{$tarefa['id']}}').submit()">Excluir</a></td>

```

no TarefaController no metodo destroy

```php
  public function destroy(Tarefa $tarefa)
    {
        $user_id = auth()->user()->id;
        $tarefa_id = $tarefa->user_id;
        if($tarefa_id == $user_id){
            $tarefa->destroy($tarefa->id);
            return redirect()->route('tarefa.index');
        }else{
            return view('acesso-negado');
        }
    }

```

## adicionando links da tarefa novo,lista

no index.blade.php incluimos o link de criar a tarefa no titulo

```html
<h3 class="card-header">Tarefas </h3><a href="{{ route('tarefa.create')}}" class="float-right">Novo</a>

```

no app.blade.php incluimos a lista de tarefa link
```html

<li class="nav-item"><a href="{{ route('tarefa.index')}}">Tarefas</a></li>


```

## Verificando se o usuario esta logado

routes/web.php
criamos a view bem-vindo

```php
Route::get('/', function () {
    return view('bem-vindo');
});

```
```php


@auth
Tudo que tiver contido aqui so aparece se o usuario esta authenticado
@endauth

```

recuperando attributes do usuario

```php

<p>ID{{ Auth::user()->id}}</p>
<p>Name{{ Auth::user()->name}}</p>
<p>Email{{ Auth::user()->email}}</p>
```

outra tag @guest e o oposto do auth

```php
@guest
  todos usuarios visitantes , consegue visualizar o que esta aqui dentro
  se tiver authenticado nao aparece
@endguest

```

## instalando o pacote Laravel Excel

composer require maatwebsite/excel=^3.1.0
composer require maatwebsite/excel=^3.1.0 --ignore-platform-reqs

config/app.php

```php
providers = [
    Maatwebsite\Excel\ExcelServiceProvider::class
];


  'aliases' => [ 'Excel' => Masstwebsite\Escel\Facades\Excel::class];

  publicando o arquivo de configuracao

php artisan vendor:publish  --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
```



## Exportando um arquivo no formarto XSLX com a relacao de tarefas

Criando uma classe de exportacao

php make:export <Nome da classe>

php artisan make:export TarefasExport -model=Tarefa

preparando a rota e o metodo para chamar a classe

routes/web.php
```php
Route::get('tarefa/exportacao', function(){
    return 'chegamos ate aqui';
});

```


incluindo o link de exportacao

views/tarefa/index.blade.php

```html
 <a href="{{ route('tarefa.exportacao')}}">XLSX</a>
 ```

acrescentado o name na rota
```php
Route::get('tarefa/exportacao', function(){
    return 'chegamos ate aqui';
})->name('tarefa.exportacao');

```

adicionamos a classe no TarefasCOntroller

```php
public function exportacao(){
        return Excel::download(new TarefasExport,'lista_tarefas.xlsx');
    }
```

## refatorando relacionamento entre Users e Tarefas

relacionamentos 1 usuario  tem muitas tarefas

no Models/User.php vamos adicionar o metodo tarefas

```php

 public function tarefas(){
            //hasMany
            return $this->hasMany('App\Models\Tarefa');
    }

```

vamos no Models tarefa.php e implementar o metodo user()

```php

  public function user(){
        return $this->belongsTo('App\Models\User');
    }
```

vamos no Export/tarefasExport.php e incluimos

```php

 public function collection()
    {
        return auth()->user()->tarefas()->get();

    }
```
## Refatorando um arquivo no formato CSV com relacao as tarefas

Criamos um novo link CSV

```html

<a href="{{ route('tarefa.exportacao')}}">CSV</a>

```

passamos um parametro extensao para passar ao COntroller

```html
<a href="{{ route('tarefa.exportacao',['extensao' => 'csv'])}}">CSV</a>
```

adicionamos o parametro no controller no metodo

```php
public function exportacao($extensao){

    }

```
adicionamos na rota que vamos receber um parametro na url

```php
Route::get('tarefa/exportacao/{extensao}', 'App\Http\Controllers\TarefaController@exportacao')->name('tarefa.exportacao');



```
recuperamos o metodo concatenando

```php
public function exportacao($extensao){
        return Excel::download(new TarefasExport,"lista_tarefas.{$extensao}");
    }

```

## Exportando um arquivo no formato PDF

composer require mpdf/mpdf=^8.0.10

acessando excel.php

pesquisamos por pdf

'pdf' => Excel::MPDF

incluimos o link e a rota

```html
<a href="{{ route('tarefa.exportacao',['extensao' => 'pdf'])}}">PDF</a>

```

trefaController

```php
public function exportacao($extensao){
    if(in_array([$extensao, 'xlsx','csv','pdf']))
        return Excel::download(new TarefasExport,"lista_tarefas.{$extensao}");
    }
    return redirect()->route('tarefa.index');

```
## definindo titulos na exportacao

na classe tarefaExport

incluimos uma interface WithHeadings

```php
use Maatwebsite\Excel\Concerns\WithHeadings;

//implementamos a classe
class TarefasExport implements FromCollection, WithHeadings{}

//criamos um metodo

public function headings(){
    return ['cada indice desse array , pertence um titulo '];
}

//preenchemos ela
public function headings():array{// declarando tipo de retorno
        return ['ID','ID do usuario', 'Tarefa','Data Limite conclusao','Data Cadastro','Data Atualizada'];
    }
```
## Corrigindo caracteres especiais

config;excel.php

```php
 'csv'                    => [
            'delimiter'              => ',',
            'enclosure'              => '"',
            'line_ending'            => PHP_EOL,
            'use_bom'                => false, // passamos para true
            'include_separator_line' => false,
            'excel_compatibility'    => false,
            'output_encoding'        => '',
            'test_auto_detect'       => true,
        ],
```



