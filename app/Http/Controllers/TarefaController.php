<?php

namespace App\Http\Controllers;

use App\Mail\NovaTarefaMail;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TarefaController extends Controller

{
    public function __construct()
    {
         $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $tarefas = Tarefa::where('user_id', $user_id)->paginate(10);
        return view('tarefa.index',['tarefas'=>$tarefas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tarefa.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

       $dados = $request->all(['tarefa','data_limite_conclusao']);
       $dados['user_id'] = auth()->user()->id;

       $tarefa = Tarefa::create($dados);

       $destinatario = auth()->user()->email;

        Mail::to($destinatario)->send(new NovaTarefaMail($tarefa));
        return redirect()->route('tarefa.show',['tarefa'=>$tarefa->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function show(Tarefa $tarefa)
    {
        return view('tarefa.show',['tarefa' =>$tarefa]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
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

        $user_id = auth()->user()->id;
        $tarefa_id = $tarefa->user_id;
        if($tarefa_id == $user_id){
            $tarefa->update($request->all());
            return redirect()->route('tarefa.show',['tarefa'=>$tarefa]);
        }else{
            return view('acesso-negado');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tarefa $tarefa)
    {
        //
    }
}
