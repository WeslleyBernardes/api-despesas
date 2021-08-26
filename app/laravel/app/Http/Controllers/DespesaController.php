<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use App\Models\User;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Mail;


class DespesaController extends Controller
{
    public function __construct(Despesa $despesa, User $user){
        $this->despesa = $despesa;
        $this->user = $user;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        
        if($request->has('filtro')){
            $condicoes = explode(':',$request->filtro);
            $despesas = $this->despesa->where($condicoes[0],$condicoes[1],$condicoes[2])->get();

        } else {
            $despesas = $this->despesa->all();
        }
        return response()->json($despesas,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->despesa->rules(),$this->despesa->feedback());

        $email  =  $this->user->query('usuario', $request->usuario)->get();


        $despesa = $this->despesa->create($request->all());
   
        $user = new \stdClass();
        $user->name  =  $email[0]['name'];
        $user->email = $email[0]['email'];
        $user->despesa = $request->descricao;
        Mail::send(new \App\Mail\SendMail($user));

        return response()->json($despesa,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $despesa = $this->despesa->find($id);
        if($despesa === null){
            return response()->json(['erro'=>'Registro informado nao foi localizado.'],404);
        }
        return response()->json($despesa,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Despesa  $despesa
     * @return \Illuminate\Http\Response
     */
    public function edit(Despesa $despesa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $despesa = $this->despesa->find($id);   
        if($despesa === null){
            return response()->json(['erro'=>'Registro informado nao foi localizado.'],404);
        }
        if($request->method() === 'PATCH'){
            $dynamicRules = array();
            foreach($despesa->rules() as $input => $rules){

                if(array_key_exists($input, $request->all())){
                    $dynamicRules[$input] = $rules;
                }
            }
            $request->validate($dynamicRules,$this->despesa->feedback());
           
        } else {
            $request->validate($this->despesa->rules(),$this->despesa->feedback());
        }
        
        $despesa->update($request->all());
        return $despesa;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Despesa  $despesa
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /*
        print_r($despesa->getAttributes());
        */
        $despesa = $this->despesa->find($id);
        if($despesa === null){
            return response()->json(['erro'=>'Registro informado nao foi localizado.'],404);
        }
        $despesa->delete();
        return ['msg'=>'O registro foi removido com sucesso!'];
    }
}
