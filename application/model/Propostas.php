<?php


use Illuminate\Database\Eloquent\Model;

class Propostas extends Model
{

    protected $table = 'proposta';
    protected $primaryKey = 'idproposta';
    protected $fillable = [
        'idproposta',
        'idcotacao',
        'idformapg',
        'quantparc',
        'dtvalidade',
        'dtcreate',
        'idstatus',
        'nmbandeira',
        'numcartao',
        'validadecartao',
        'idmotivo',
        'cvvcartao',
        'premiototal',
        'primeiraparc',
        'demaisparc',
        'usuario_id',
        'titularcartao',
        'veiculo_id',
        'segurado_id'

    ];


    public function cotacao()
    {
        return $this->belongsTo(Cotacoes::class, 'idcotacao', 'idcotacao');
    }

    public function cotacaoseguradora()
    {
        return $this->hasOne(CotacoesSeguradora::class, 'id_proposta_sap', 'idproposta');
    }
    public function formapg()
    {
        return $this->hasOne(FormaPagamento::class, 'idformapgto', 'idformapg');
    }

    public function propostaseguradora()
    {
        return $this->hasOne(PropostasSeguradora::class, 'id_proposta_sap', 'idproposta');
    }
    public function cobranca()
    {
        return $this->hasOne(Cobranca::class, 'idproposta', 'idproposta')->with(['segurado.estadocivil','veiculo'=>function($q){
            $q->with('combustivel');
            $q->with('fipe');
        }]);
    }
    
    public function apoliceseguradora()
    {
        return $this->hasMany(ApolicesSeguradora::class, 'id_proposta_sap', 'idproposta');
    }


    public function certificado()
    {
        return $this->hasOne(Certificados::class,'idproposta','idproposta');
    }

    public function veiculo()
    {
        return $this->belongsTo(Veiculos::class,'veiculo_id','veicid');
    }
    
    
    
    
    

   


}
