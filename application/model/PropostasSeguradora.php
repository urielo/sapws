<?php


use Illuminate\Database\Eloquent\Model;

class PropostasSeguradora extends Model
{

    protected $table = 'proposta_seguradora';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_proposta_sap',
        'id_config_seguradora',
        'id_cotacao_seguradora',
        'id_proposta_seguradora',
        'id_endesso_seguradora',
        'cd_retorno_seguradora',
        'nm_retorno_seguradora',
        'dt_criacao',
        'dt_update',
        'xml_saida',
        'xml_retorno',
    ];
    
    public $timestamps = FALSE;

    

    public function proposta()
    {
        return $this->belongsTo(Propostas::class, 'id_proposta_sap', 'idproposta');
    }
    
    public function cotacaoseguradora()
    {
        return $this->belongsTo(CotacoesSeguradora::class, 'id_cotacao_seguradora', 'id_cotacao_seguradora');
    }

    


}
