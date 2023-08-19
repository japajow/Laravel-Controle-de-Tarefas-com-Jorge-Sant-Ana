<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        .titulo{
            background-color:darkgray;
            padding:8px;
            border: 1px;
            text-align: center;
            width:100%;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .tabela{
            width: 100%;

        }

        table th{
            text-align: left;
        }
    </style>

</head>
<body>
    <h2 class="titulo">Lista de tarefas</h2>

    <table class="tabela">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tarefa</th>
                <th>Data limite concluido</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($tarefas as $key => $tarefa )
                <tr>
                    <td>{{ $tarefa->id}}</td>
                    <td>{{ $tarefa->tarefa}}</td>
                    <td>{{ date('d/m/Y', strtotime($tarefa->data_limite_conclusao))}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
