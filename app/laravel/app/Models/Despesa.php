<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    use HasFactory;
    protected $fillable = ['descricao', 'data', 'usuario','valor'];

    public function rules(){
        return [
            'descricao'=>'max:191',
            'usuario'=>'required|exists:users,name',
            'valor'=>'numeric|gte:0',
            'data'=>'before:'.date('Y-m-d'),

        ];
    }
    public function feedback(){
        return  [
            'usuario.exists'=>'Usuário não encontrado.',
            'valor.numeric'=>'Deve ser informado um valor positivo',
            'valor.gte'=>'Deve ser informado um valor positivo',
            'descricao.max'=>'A descrição deve ter no máximo 191 caracteres.',
            'require'=>'O campo :attribute é obrigatório',
            'data.before'=>'A data não pode ser futura',
        ];
    }
}
