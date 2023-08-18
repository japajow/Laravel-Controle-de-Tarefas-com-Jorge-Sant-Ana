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
                            <button type="submit" class="btn btn-warning">Atualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
