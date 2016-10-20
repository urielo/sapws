<?php


use Illuminate\Database\Eloquent\Model;

class ConfigSeguradora extends Model
{

    protected $table = 'config_seguradora';
    protected $primaryKey = 'id';
    protected $fillable = [

        'id_seguradora',
        'id_revenda',
        'cd_produto',
        'mes_periodo_virgencia',
        'nm_usuario',
        'id_tipo_veiculo',
        'cd_forma_pagamento',
        'id_produto_parcela_premio',
        'nm_resp1_gov',
        'nm_resp2_gov',
        'iof',

    ];
    
    public $timestamps = FALSE;

    
    public function seguradora()
    {
     return $this->belongsTo(Seguradora::class,'id_seguradora','idseguradora');
    }
    

   

    


}
