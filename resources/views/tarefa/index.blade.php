@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h3 class="card-header">Tarefas</h3>

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
                                <td><a href="{{ route('tarefa.edit',$tarefa['id']) }}">Editar</a></td>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                          <div class="d-flex justify-content-center align-items-center">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                  <li class="page-item"><a class="page-link" href="{{ $tarefas->previousPageUrl() }}">Voltar</a></li>
                                    @for ($i=1;$i<=$tarefas->lastPage(); $i++)
                                    <li class="page-item"><a class="page-link {{ ($tarefas->currentPage() == $i)? 'active' : ''}}" href="{{ $tarefas->url($i) }}">{{ $i }}</a></li>
                                    @endfor

                                  <li class="page-item"><a class="page-link" href="{{ $tarefas->nextPageUrl() }}">Proximo</a></li>
                                </ul>
                              </nav>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
