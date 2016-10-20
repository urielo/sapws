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
        'clicpfcnpj',
        'veicid',
        'premio',
        'comissao',
        'idstatus',
        'dtvalidade',
        'dtcreate',
        'dtupdate',
        'usuario_id',
    ];
    public $timestamps = FALSE;
    
    public function veiculo()
    {
        return $this->belongsTo(Veiculos::class,'veicid','veicid');
    }
    
    public function corretor()
    {
        return $this->belongsTo(Corretores::class,'idcorretor','idcorretor');
    }
    public function segurado()
    {
        return $this->belongsTo(Segurado::class,'clicpfcnpj','clicpfcnpj');
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
