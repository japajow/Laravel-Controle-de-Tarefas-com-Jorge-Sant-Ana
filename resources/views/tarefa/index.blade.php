@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Tarefas
                       <div class="d-flex justify-content-between">
                        <a href="{{ route('tarefa.create')}}" class="btn btn-sm btn-success">NOVO</a>
                        <a href="{{ route('tarefa.exportacao',['extensao' => 'xlsx'])}}" class="btn btm-sm btn-primary">XLSX</a>
                        <a href="{{ route('tarefa.exportacao',['extensao' => 'csv'])}}" class="btn btm-sm btn-primary">CSV</a>
                        <a href="{{ route('tarefa.exportar')}}" target="_blank" class="btn btm-sm btn-primary">PDF</a>
                       </div>
                    </div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                              <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Tarefa</th>
                                <th scope="col">Data Limite conclusao</th>
                                <th scope="col" colspan="2" class="text-center">Ações</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($tarefas as $tarefa )
                              <tr>
                                <th scope="row">{{ $tarefa->id}}</th>
                                <td>{{ $tarefa->tarefa}}</td>
                                <td>{{ date('d/m/Y', strtotime($tarefa->data_limite_conclusao))}}</td>
                                <td><a href="{{ route('tarefa.edit',$tarefa['id']) }}">Editar</a></td>
                                <td><form id="form-{{$tarefa['id']}}" action="{{ route('tarefa.destroy',['tarefa'=>$tarefa['id']])}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                    <a href="#" onclick="document.getElementById('form-{{$tarefa['id']}}').submit()">Excluir</a></td>
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
