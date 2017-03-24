<?php

use Illuminate\Database\Eloquent\Model;

class Cotacoes extends Model
{

    protected $table = 'cotacao';
    protected $primaryKey = 'idcotacao';
    protected $fillable = [
        'idcotacao',
        'idparceiro',
        'idcorretor',
        'segurado_id',
        'veicid',
        'premio',
        'comissao',
        'idstatus',
        'validade',
        'dtcreate',
        'dtupdate',
        'usuario_id',
        'renova',
        'code_fipe',
        'ano_veiculo',
        'combustivel_id',
        'tipo_veiculo_id',
        'ind_veiculo_zero',
        'validade',
    ];
    

    
    public function corretor()
    {
        return $this->belongsTo(Corretores::class,'idcorretor','idcorretor');
    }
    public function prospect()
    {
        return $this->hasOne(Prospect::class,'id_cotacao','idcotacao');
    }
    public function segurado()
    {
        return $this->belongsTo(Segurado::class,'segurado_id','id');
    }
    public function parceiro()
    {
        return $this->belongsTo(Parceiros::class,'idparceiro','idparceiro');
    }

    public function proposta()
    {
        return $this->hasOne(Propostas::class,'idcotacao','idcotacao');
    }
    
    public function produtos()
    {
        return $this->hasMany(CotacaoProdutos::class,'idcotacao','idcotacao');
    }
    
    public function status(){
        return $this->belongsTo(Status::class,'idstatus','id');

    }
    

}
