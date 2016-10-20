<?php

use Illuminate\Database\Eloquent\Model;

class CotacoesSeguradora extends Model
{

    protected $table = 'cotacao_seguradora';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_proposta_sap',
        'id_config_seguradora',
        'id_cotacao_seguradora',
        'premio_tarifario_seguradora',
        'lmi_seguradora',
        'iof_seguradora',
        'franquia_seguradora',
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

    


}
